# Satis automation hooks

Some usage notes:

## Git repository setup
Since __git__ adhere to "one repository - one project" philosophy, just create `post-commit` hook with `curl` POST'ing `update=<vendor>/<project>` to your webhook script.

## Subversion repository hook script
Script requirements:

1. Subversion tools - svnlook in particular (quite obviously).
2. jq - to talk to `composer.json`.
3. curl - to call the webhook.

### Subversion hook files and usage tips:
#### `.satis-projects.sample` -> `$HOME/.satis-projects`
The list of projects to monitor.

List structure is a colon-separated lines, each describing one hooked repository.

Supported formats are:

1. Bare project repository (`$SVNROOT/{trunk,branches,tags}`).
2. Project is a subdirectory in a common repository (`$SVNROOT/project/{trunk,branches,tags}`).
    1. Subdirectory could be one or two levels deep. Not more.

Mixing bare and prefix projects in the same repository is not supported - you will end up always updating the former.

#### `Subversion.hooks` -> `$SVNROOT/hooks`
The (better) hooks setup for Subversion.

If you are already using Subversion hooks, move your hooks to `hooks/<hook>.d/` directory and substitute provided activator.

#### `Subversion.hooks/environment`
Environment setup for hooks. More useful than Subversion's tricky `hooks-env` magic.

#### `Subversion.hooks/post-commit`
The hook activator example. __Do not use symlinks for hook activators.__ Copy activator into appropriate hook name and create `<hook>.d/` directory for actual hooks.

#### `Subversion.hooks/post-commit.d/`
Directory for actual hooks. See `run-parts(8)` for details about scripts naming and execution ordering.

#### `Subversion.hooks/post-commit.d/satis-poller`
An actual satis hook script.

## Satis server side

#### `satis-webhook.php`
The webhook endpoint. Location depend on server configuration. If you are sharing endpoint with satis itself, it is strongly recommended to limit script execution to this one script only.

#### `satis-cron-job.sh` -> `~/bin/satis-cron-job.sh`
The repository updater (`satis build ...`) wrapper. Tell cron to tab it with desired interval.

Regular execution will look for `~/tmp/satis-update` and try to update any projects found there.

Alternatively, running with `-f` switch will force update of all configured projects.
