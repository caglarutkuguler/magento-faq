# Changelog

All notable changes to Megventure_Faq are recorded here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project uses
[semantic versioning](https://semver.org/).

## [1.0.0] — 2026-09-02

First release: the Magento port of our PrestaShop `megfaq` module.

### Added

- FAQ entries, each with a question and an answer, stored per store view with
  the default store's text as a fallback.
- Shared entries (product ID 0) on every product page, and entries attached to
  a single product, which sort above the shared ones.
- A FAQ page at `/faq`, published through a URL rewrite that steps aside if the
  shop already has a page on that path.
- Admin management under Content → Product FAQ: a grid, an entry form, and a
  store-view switcher.
- Configuration under Stores → Configuration → Megventure → Product FAQ: where
  the entries show, the heading, whether the first entry starts open, and
  whether to fall back to the default text.
- Storefront output in plain `<details>` elements, rendered server-side with no
  JavaScript.
- Translations for English, Turkish, German, French, Spanish, Italian, Dutch,
  Polish and Portuguese.
