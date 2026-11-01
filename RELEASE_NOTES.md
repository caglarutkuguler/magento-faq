# Product FAQ for Magento 2 — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 1.0.0 — 2026-09-02

### New
- FAQ entries, each with a question and an answer, stored per store view with the default store's text as a fallback
- Shared entries (product ID 0) on every product page, and entries attached to a single product, which sort above the shared ones
- A FAQ page at `/faq`, published through a URL rewrite that steps aside if the shop already has a page on that path
- Admin management under Content → Product FAQ: a grid, an entry form, and a store-view switcher
- Configuration under Stores → Configuration → Megventure → Product FAQ: where the entries show, the heading, whether the first entry starts open, and whether to fall back to the default text
- Storefront output in plain `<details>` elements, rendered server-side with no JavaScript, and a small stylesheet so they read as an openable list on a theme that styles `<details>` no further than the browser default
- Translations for English, Turkish, German, French, Spanish, Italian, Dutch, Polish and Portuguese

### Improved
- First release: the Magento port of our PrestaShop `megfaq` module
