<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use humhub\components\Widget;

/**
 * @package GameCenter/Widgets
 */
class GameCard extends Widget
{

  public Game $game;

  /**
   * @inheritdoc
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run()
  {
    return $this->render('gameCard', ['game' => $this->game]);
  }

}
