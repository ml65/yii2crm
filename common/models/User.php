<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    const ROLE_GUEST = 0;
    const ROLE_ADMIN = 1024;
    const ROLE_MANAGER = 512;
    const ROLE_USER = 1;

    protected static $statusTitles = [
        self::STATUS_DELETED  => 'Удален',
        self::STATUS_INACTIVE => 'Не активирован',
        self::STATUS_ACTIVE   => 'Активирован'
    ];
    protected static $rolesTitles = [
        self::ROLE_GUEST    => 'Гость',
        self::ROLE_USER     => 'Пользователь',
        self::ROLE_MANAGER  => 'Менеджер',
        self::ROLE_ADMIN    => 'Админ'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_USER, self::ROLE_GUEST]], // пока не смешиваем роли
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return array
     */
    public static function getAviableRoles($all = false)
    {
        if ($all) {
            return [
                null => Yii::t('user', "Все"),
                self::ROLE_GUEST   => Yii::t('user', static::$rolesTitles[self::ROLE_GUEST]),
                self::ROLE_USER    => Yii::t('user', static::$rolesTitles[self::ROLE_USER]),
                self::ROLE_MANAGER => Yii::t('user', static::$rolesTitles[self::ROLE_MANAGER]),
                self::ROLE_ADMIN   => Yii::t('user', static::$rolesTitles[self::ROLE_ADMIN]),
            ];

        } else {
            return [
                self::ROLE_GUEST   => Yii::t('user', static::$rolesTitles[self::ROLE_GUEST]),
                self::ROLE_USER    => Yii::t('user', static::$rolesTitles[self::ROLE_USER]),
                self::ROLE_MANAGER => Yii::t('user', static::$rolesTitles[self::ROLE_MANAGER]),
                self::ROLE_ADMIN   => Yii::t('user', static::$rolesTitles[self::ROLE_ADMIN]),
            ];
        }
    }

    /**
     * @param $roles
     * @return string
     */
    public static function getRoleTitle($roles)
    {
        if (array_key_exists($roles, static::$rolesTitles)) {
            return self::$rolesTitles[$roles];
        } else {
            return '';
        }
    }

    /**
     * @param $roles
     * @return string
     */
    public static function getStatusTitle($status)
    {
        if (array_key_exists($status, static::$statusTitles)) {
            return self::$statusTitles[$status];
        } else {
            return '';
        }
    }

    public static function getAviableStatus($all = false)
    {
        if ($all) {
            return [
                null => Yii::t('user', "Все"),
                self::STATUS_INACTIVE => Yii::t('user', static::$statusTitles[self::STATUS_INACTIVE]),
                self::STATUS_ACTIVE   => Yii::t('user', static::$statusTitles[self::STATUS_ACTIVE]),
                self::STATUS_DELETED  => Yii::t('user', static::$statusTitles[self::STATUS_DELETED]),
            ];
        } else {
            return [
                self::STATUS_INACTIVE => Yii::t('user', static::$statusTitles[self::STATUS_INACTIVE]),
                self::STATUS_ACTIVE   => Yii::t('user', static::$statusTitles[self::STATUS_ACTIVE]),
                self::STATUS_DELETED  => Yii::t('user', static::$statusTitles[self::STATUS_DELETED]),
            ];
        }
    }

    public function isAdmin()
    {
        $adminrole = $this->role & User::ROLE_ADMIN;
        return $adminrole == User::ROLE_ADMIN;
    }

}
