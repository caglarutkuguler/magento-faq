# Product FAQ for Magento 2 — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 1.0.1 — 2026-09-02

### Fixed
- The /faq rewrite deleted other modules' rewrites. It was stored under the entity type Magento shares between modules, and Magento's rewrite save removes every row matching the entity type, entity id and store it writes — so installing this module deleted any rewrite another module had put there. Our own AI Visibility Toolkit lost its /llms.txt this way, silently: the URL simply started answering 404. The rewrite now lives under a type only this module writes, and upgrading moves the existing row across

## 1.0.0 — 2026-09-02

### New
- FAQ entries, each with a question and an answer, stored per store view with the default store's text as a fallback
- Shared entries (product ID 0) on every product page, and entries attached to a single product, which sort above the shared ones
- A FAQ page at `/faq`, published through a URL rewrite that steps aside if the shop already has a page on that path
- Admin management under Content → Product FAQ: a grid, an entry form, and a store-view switcher
- Configuration under Stores → Configuration → Megventure → Product FAQ: where the entries show, the heading, whether the first entry starts open, and whether to fall back to the default text
- Storefront output in the browser's own expandable details element, rendered server-side with no JavaScript, and a small stylesheet so it reads as an openable list on a theme that leaves that element at the browser default
- Translations for English, Turkish, German, French, Spanish, Italian, Dutch, Polish and Portuguese
