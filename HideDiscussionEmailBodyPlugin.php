<?php

namespace APP\plugins\generic\hideDiscussionEmailBody;

use PKP\mail\Mailable;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

class HideDiscussionEmailBodyPlugin extends GenericPlugin
{
    public function register($category, $path, $mainContextId = null): bool
    {
        if (parent::register($category, $path, $mainContextId)) {
            if ($this->getEnabled()) {
                Hook::add('Mailable::build', [$this, 'handleMailableBuild']);
            }
            return true;
        }
        return false;
    }

    public function handleMailableBuild(string $hookName, Mailable $mailable): bool
    {
        $class = get_class($mailable);
        if (
            str_contains($class, 'DiscussionCopyediting') ||
            str_contains($class, 'DiscussionProduction') ||
            str_contains($class, 'DiscussionReview') ||
            str_contains($class, 'DiscussionSubmission')
        ) {
            $date = date('Y-m-d H:i', time());

            $mailable->body(
                __('plugins.generic.hideDiscussionEmailBody.discussion.updated')
                . '<p><small>' . $date . '</small></p>'
            );

            // Remove attachments
            $mailable->attachments = [];
        }
        return Hook::CONTINUE;
    }

    public function getDisplayName(): string
    {
        return __('plugins.generic.hideDiscussionEmailBody.displayName');
    }

    public function getDescription(): string
    {
        return __('plugins.generic.hideDiscussionEmailBody.description');
    }
}
