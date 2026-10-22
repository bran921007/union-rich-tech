# PHP and Laravel migration

Require patched PHP8.2.35 / 8.3.36 / 8.4.27 / 8.5.12 branches and upgrade Laravel8 to Laravel12, Jetstream2 to5, Livewire2 to3 and Sanctum2 to4. The lockfile was resolved and tested on PHP8.2.35.

Existing User/Team namespaces and routes stay in place. Registration and team policies use the existing model namespaces. Published Jetstream components retain their tag names and styles; Livewire owns Alpine. The supported Bacon QR writer preserves 200px SVG generation. Published email content is reused at Jetstream5's view path. Rebuilt Mix assets are included because this repository tracks compiled output.

The additive migration adds nullable token expiration without deleting existing tokens. Before a future deployment, review and apply this migration using the project's normal backup/release process. Preserve the existing APP_KEY, configuration and data. This change performs no deployment.

Validation: PHP8.2.35 synthetic SQLite suite, 46 tests / 80 assertions /7 feature-dependent skips, no failures or risky tests; frontend production build; Composer validation/platform checks. Composer resolution reported no security advisories. Execution used network-disabled containers; no real mail, payments or external service calls were made. Higher PHP branches and browser interactions are not runtime-tested. Legacy database migrations are exercised on fresh synthetic SQLite, not production MySQL data.
