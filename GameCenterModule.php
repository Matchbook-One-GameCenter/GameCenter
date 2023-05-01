<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter;

use fhnw\modules\gamecenter\notifications\AchievementUnlocked;
use humhub\components\Module;
use Yii;
use yii\helpers\Url;

/**
 * The GameCenter Module
 *
 * @package GameCenter
 * @property-read string $configUrl
 * @property-read string $name
 * @property-read string $description
 * @property-read bool   $isActivated
 * @property-write array $aliases        List of path aliases to be defined.
 * @property string      $basePath       The root directory of the module.
 * @property string      $controllerPath The directory that contains the controller classes.
 * @property string      $layoutPath     The root directory of layout files. Defaults to '[[viewPath]]/layouts'.
 * @property array       $modules        The modules (indexed by their IDs).
 * @property-read string $uniqueId       The unique ID of the module.
 * @property string      $version        The version of this module.
 * @property string      $viewPath       The root directory of view files. Defaults to '[[basePath]]/views'.
 */
class GameCenterModule extends Module
{

  /**
   * @var string $icon defines the icon
   * @static
   */
  public static string $icon = 'gamepad';
  /** @var string $controllerNamespace */
  public $controllerNamespace = 'fhnw\modules\gamecenter\controllers';
  /** @var string $resourcesPath defines path for resources, including the screenshots path for the marketplace */
  public $resourcesPath = 'resources';

  /** @return void */
  public function init(): void
  {
    parent::init();
    $this->registerTranslations();
  }

  /**
   * Translates a message to the specified language.
   *
   * @param string   $category the message category.
   * @param string   $message  the message to be translated.
   * @param string[] $params   the parameters that will be used to replace the corresponding placeholders in the message.
   * @param ?string  $language the language code (e.g. `en-US`, `en`).
   *
   * @return string the translated message.
   */
  public static function t(string $category, string $message, array $params = [], string $language = null): string
  {
    return Yii::t("gamecenter/$category", $message, $params, $language);
  }

  /**
   * @inheritdoc
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function getConfigUrl(): string
  {
    return Url::to(['/gamecenter/admin']);
  }

  /**
   * Returns modules description provided by module.json file
   *
   * @return string
   */
  public function getDescription(): string
  {
    return GameCenterModule::t('base', 'Manage Games');
  }

  /**
   * Returns modules name provided by module.json file
   *
   * @return string Name
   */
  public function getName(): string
  {
    return GameCenterModule::t('base', 'GameCenter');
  }

  /** @return array<class-string> */
  public function getNotifications(): array
  {
    return [
      AchievementUnlocked::class
    ];
  }

  /**
   * @return void
   */
  private function registerTranslations(): void
  {
    Yii::$app->i18n->translations['gamecenter*'] = [
      'class'          => 'yii\i18n\PhpMessageSource',
      'sourceLanguage' => 'en',
      'basePath'       => '@gamecenter/messages'
    ];
  }
}
