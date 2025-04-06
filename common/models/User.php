<?php

namespace common\models;

use common\interfaces\AuthInterface;
use common\interfaces\PasswordInterface;
use common\interfaces\RoleInterface;
use common\services\AuthService;
use common\services\PasswordService;
use common\services\RoleService;
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

    protected static $statusTitles = [
        self::STATUS_DELETED  => 'Удален',
        self::STATUS_INACTIVE => 'Не активирован',
        self::STATUS_ACTIVE   => 'Активирован'
    ];

    private ?AuthService $authService = null;
    private ?PasswordService $passwordService = null;
    private ?RoleService $roleService = null;

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
            ['role', 'default', 'value' => RoleService::ROLE_USER],
            ['role', 'in', 'range' => [RoleService::ROLE_ADMIN, RoleService::ROLE_USER, RoleService::ROLE_GUEST]],
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
        return (new PasswordService(''))->isPasswordResetTokenValid($token);
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
        return $this->getAuthService()->validateAuthKey($authKey);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->getAuthService()->validatePassword($password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->getPasswordService()->setPassword($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = $this->getAuthService()->generateAuthKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = $this->getPasswordService()->generatePasswordResetToken();
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
        $this->getPasswordService()->removePasswordResetToken();
    }

    /**
     * @return array
     */
    public static function getAviableRoles($all = false)
    {
        return (new RoleService(0))->getAvailableRoles($all);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getRoleService()->isAdmin();
    }

    /**
     * @return bool
     */
    public function isManager()
    {
        return $this->getRoleService()->isManager();
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->getRoleService()->isUser();
    }

    private function getAuthService(): AuthInterface
    {
        if ($this->authService === null) {
            $this->authService = new AuthService($this->password_hash, $this->auth_key);
        }
        return $this->authService;
    }

    private function getPasswordService(): PasswordInterface
    {
        if ($this->passwordService === null) {
            $this->passwordService = new PasswordService($this->password_hash, $this->password_reset_token);
        }
        return $this->passwordService;
    }

    private function getRoleService(): RoleInterface
    {
        if ($this->roleService === null) {
            $this->roleService = new RoleService($this->role);
        }
        return $this->roleService;
    }
}
