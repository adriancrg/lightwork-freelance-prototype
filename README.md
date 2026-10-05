# LightWork: freelance marketplace with Bitcoin Lightning escrow

A web marketplace where freelancers post service offers and buyers take them, talk to the seller in a chat and
(in the design) pay through the **Bitcoin Lightning Network** with funds held in escrow until the work is delivered.

It is a university-era project built with **PHP, MySQL and plain HTML/CSS/JavaScript**, run locally on **XAMPP**.
The user accounts, offers with photos, search and the persisted buyer/seller chat work. The Lightning escrow
(LND hold invoices) is **partially implemented**: the building blocks exist, but I have not wired them into a
complete, tested payment flow. See [Status](#status) for exactly what works and what does not.

## Status

| Area | State | Notes |
|---|---|---|
| Sign-up / login / logout | Working | PHP sessions, MySQL `usuarios` table, prepared statements |
| Post an offer with photos | Working | Title, description, category, price (sats), several images saved to `assets/offer_images/` |
| Browse and filter offers | Working | Text search plus category and price filters, cards loaded from MySQL (`php/get_offers.php`) |
| Offer detail page | Working | Photo gallery, seller, price, "take this offer" |
| Take an offer and chat | Working | Creates a `conversations` row; messages stored in `chat_messages` and refreshed by AJAX polling every second |
| Lightning hold-invoice escrow | **Partial** | Node.js scripts using the `lightning` package to create and settle an LND hold invoice, triggered from PHP. Amount is hard-coded to 1000 sats, the preimage is not persisted, the "Mark as paid" / "Cancel" buttons have no handlers and the status tracker is static. Not tested end to end |
| User profile page | Mock-up | Static sample data, not read from the database |
| Landing / about / contact pages | Working | Contact button points to a placeholder Telegram link |

## Features

* **Accounts**: registration with e-mail and username, login, session handling, logout.
* **Offers**: sellers publish services with a category, a price in satoshis and one or more images.
* **Marketplace**: search and filter offers by category and price; each offer has its own detail page.
* **Chat**: once a buyer takes an offer, buyer and seller get a conversation page whose messages are stored in the
  database and polled via AJAX.
* **Lightning escrow (work in progress)**: the buyer pastes a hold invoice, a Node script registers it with the
  seller's LND node, and a second script settles it with the secret once the buyer confirms the service.
* **Interface**: hand-written CSS (cards, gallery), custom web font, some responsive rules.

## How the escrow is meant to work

1. Buyer and seller agree on a service in the chat.
2. A **hold invoice** (HODL invoice) is created on the Lightning node: the buyer's payment is accepted but not yet
   settled, so the funds are locked and the seller cannot spend them yet. (`lightning/create_hold_invoice.js`,
   called from `lightning/submit_invoice.php`.)
3. When the work has been delivered the buyer confirms, and the invoice is **settled** with its preimage, which
   releases the funds to the seller. (`lightning/settle_hold_invoice.js`, called from `lightning/confirm_service.php`.)
4. If the service is not delivered the invoice would be cancelled and the buyer's funds returned. This branch is not implemented.

## Tech stack

* **Back end**: PHP 8 (procedural, `mysqli` with prepared statements), PHP sessions
* **Database**: MySQL / MariaDB (XAMPP)
* **Front end**: HTML, CSS, vanilla JavaScript (AJAX with `XMLHttpRequest`)
* **Lightning**: Node.js with the [`lightning`](https://www.npmjs.com/package/lightning) package (gRPC client for LND), called from PHP through `shell_exec`
* **Local server**: XAMPP (Apache + MySQL)

## Project structure

```
index.php, about.php, contact.php        public pages
login.php                                sign-in / sign-up screen
upload.php, offer_uploaded_succesfully.php   post an offer
browse.php, display_offer.php            marketplace and offer detail
taken_offer.php                          chat + escrow panel for a taken offer
user_profile.php                         profile page (static mock-up)
php/
  conexion_be.php                        MySQL connection
  SU.php, SI.php, LO.php                 sign-up, sign-in, log-out handlers
  functions.php                          helpers (login, conversations, messages)
  upload_be.php                          offer + image upload handler
  get_offers.php                         JSON list of offers for the browse page
  update_chat.php                        chat polling / message endpoint
lightning/
  connect.js                             LND gRPC connection (cert + macaroon from env)
  create_hold_invoice.js, settle_hold_invoice.js, hold_invoice.js
  submit_invoice.php, confirm_service.php   PHP entry points that call the Node scripts
  node.env.example                       template for the LND settings
database/schema.sql                      tables used by the code
assets/                                  css, js, fonts, images, uploaded offer photos
```

## Run it locally (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy this folder into XAMPP's web root, for example `C:\xampp\htdocs\lightwork`.
3. Create the database: open phpMyAdmin (`http://localhost/phpmyadmin`) and import `database/schema.sql`
   (or run `mysql -u root < database/schema.sql`). This creates the database `signinsignup_db`.
4. The connection settings are in `php/conexion_be.php` (`localhost`, user `root`, empty password: XAMPP defaults).
5. Open `http://localhost/lightwork/`, sign up and post an offer. To test the chat, register a second user in another browser, open the offer and take it.

`database/schema.sql` is **reconstructed from the queries in the code**. Table and column names match what the PHP
uses (including the `Descriptionn` spelling); data types and indexes are my reconstruction, since the original dump
was not kept.

### Optional: Lightning module

Needs a running LND node (testnet or regtest recommended) and Node.js.

```bash
cd lightning
npm install lightning
cp node.env.example node.env      # LND host, TLS cert and macaroon (base64); never commit this file
# connect.js reads these values from environment variables (or ../tls.cert and ../admin.macaroon),
# so export them in the environment that runs PHP; it does not load node.env by itself.
```

The scripts are only reachable from `taken_offer.php`. This part is experimental, see the known issues below.

## Known issues and security notes

This is a learning project and is **not safe to expose to the internet** in its current state.

* **Password hashing**: passwords are stored as unsalted SHA-256. They should use `password_hash()` / `password_verify()` (the verify call is already present in `functions.php`, commented out).
* **No CSRF protection** on any form.
* **Authorization gaps**: `php/update_chat.php` and the `lightning/*.php` endpoints do not check the session or whether the user belongs to the conversation.
* **Image upload**: files are renamed with `uniqid()` but their type and size are not validated. Only images should be accepted (MIME check, extension allow-list, size limit).
* **Shell calls**: the Lightning PHP files pass request data to `shell_exec("node ...")`. Input must be validated and escaped with `escapeshellarg()`, or the call replaced by a proper service.
* **Escrow logic is incomplete**: hard-coded amount (1000 sats), the preimage/secret is not stored, the buttons "Mark as paid" and "Cancel" are not connected, and the status tracker does not reflect real invoice state.
* `lightning/hold_invoice.js` requires `connect.js` without `./`, and the `lightning` dependency is not listed in `package.json`.
* The upload form labels the price in USD while offers are displayed in sats.
* The profile page uses static sample data.
* Styles are split in two stylesheets and several pages have inline CSS.

## Assets and licences

The photos in `assets/images/` (category images) and the font `BNBobbieSans` were taken from third-party sources
and are included only for this non-commercial demo; check their licences before reusing them. The Telegram link on
the contact page is a placeholder.

## What I would do next

1. Replace SHA-256 with `password_hash()`, add CSRF tokens, session checks on every endpoint and upload validation.
2. Finish the escrow state machine: store invoice, amount and preimage per conversation; add real "paid", "settled" and "cancelled" states driven by LND (invoice subscription) instead of static buttons.
3. Call LND from a small Node/Express service instead of `shell_exec`.
4. Load the profile page from the database and add ratings once an order is completed.
5. Replace polling with WebSockets or server-sent events for the chat.
6. Add tests and run everything in Docker (PHP + MySQL + a regtest LND) so it can be started with one command.
