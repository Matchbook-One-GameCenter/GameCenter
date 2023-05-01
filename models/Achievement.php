<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\DateTime;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\components\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for the table "player_achievement".
 *
 * @property int $id
 * @property int $progress
 * @property float $percent_completed A percentage value that states how far the player has progressed on the
 *           achievement.
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property-read Player $player
 * @property-read AchievementDescription $achievementDescription
 * @property-read Game $game
 */
class Achievement extends ActiveRecord
{
  public const TABLE = 'player_achievement';

  /** @var string $description_id The identifier for the Achievement Description. */
  private string $description_id;

  /** @var string $player_id The identifier for the Player. */
  private string $player_id;

  /**
   * @inheritdoc
   * @static
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return self::TABLE;
  }

  /**
   * @inheritdoc
   * @return array<string,string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function attributeLabels()
  {
    return [
      'id'               => 'ID',
      'description'      => GameCenterModule::t('base', 'Achievement Description'),
      'percentCompleted' => GameCenterModule::t('base', 'Percent completed'),
      'player'           => GameCenterModule::t('base', 'Player'),
      'lastReportedDate' => GameCenterModule::t('base', 'Last Reported Date'),
      'isCompleted'      => GameCenterModule::t('base', 'Is Completed')
    ];
  }

  /**
   * This method is called at the beginning of inserting or updating a record.
   *
   * @inheridoc
   *
   * @param bool $insert whether this method called while inserting a record.
   *                     If `false`, it means the method is called while updating a record.
   *
   * @return bool whether the insertion or updating should continue.
   *              If `false`, the insertion or updating will be cancelled.
   */
  public function beforeSave($insert): bool
  {
    if (!isset($this->player_id)) {
      $this->player_id = Yii::$app->user->id;
    }

    return parent::beforeSave($insert);
  }

  /**
   * @returns ActiveQuery
   */
  public function getAchievementDescription(): ActiveQuery
  {
    return $this->hasOne(AchievementDescription::class, ['id' => 'description_id']);
  }

  /**
   * getGame
   *
   * @return \yii\db\ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id'])
                ->via('achievement');
  }

  /**
   * The player who earned the achievement.
   *
   * @return \yii\db\ActiveQuery
   */
  public function getPlayer(): ActiveQuery
  {
    return $this->hasOne(Player::class, ['id' => 'player_id']);
  }

  /**
   * A Boolean value that states whether the player has completed the achievement.
   *
   * @return bool
   */
  public function isCompleted(): bool
  {
    return $this->percent_completed == 100.0;
  }

  /**
   * The last time your game reported progress on the achievement for the player.
   *
   * @return \DateTime
   */
  public function lastReportedDate(): \DateTime
  {
    return DateTime::date($this->updated_at);
  }

  /**
   * @inheritdoc
   * @return mixed[]
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): mixed
  {
    return [
      [['percent_completed'], 'float', 'min' => 0, 'max' => 100]
    ];
  }
}
