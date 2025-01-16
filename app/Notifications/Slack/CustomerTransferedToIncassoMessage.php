<?php

declare(strict_types=1);

namespace App\Notifications\Slack;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class CustomerTransferedToIncassoMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage())
            ->headerBlock('Klant overgezet naar incasso')
            ->contextBlock(function (ContextBlock $block): void {
                $block->text('Factuur: [Duck Duck Go](https://duckduckgo.com)')->markdown();
            })
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block): void {
                $block->text('Klantgegevens:');
                $block->field("*Volledige naam:*\ntodo")->markdown();
                $block->field("*Nummer:*\ntodo")->markdown();
            });
    }
}
