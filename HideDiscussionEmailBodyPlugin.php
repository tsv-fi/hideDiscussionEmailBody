<?php

/**
 * @file HideDiscussionEmailBodyPlugin.php
 *
 * Copyright (c) 2014-2026 Simon Fraser University
 * Copyright (c) 2014-2026 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.hideDiscussionEmailBody
 *
 * @class HideDiscussionEmailBodyPlugin
 *
 * @brief HideDiscussionEmailBodyPlugin main class
 */

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
            $mailable->body(__("plugins.generic.hideDiscussionEmailBody.discussion.updated"));
        }
        return Hook::CONTINUE;
    }

    public function getDisplayName(): string
    {
        return 'Hide discussion messages from emails';
    }

    public function getDescription(): string
    {
        return 'Hides the discussion message from the email notification send to users and forces the user to login and answer to the discussion message using the discussion tool.';
    }
}
