# Product FAQ for Magento 2 — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 1.1.0 — 2026-11-24

### New
- Hyvä compatibility: FAQ page uses a one-column layout; heading, answer and link styles restated for Hyvä

## 1.0.2 — 2026-11-23

### Fixed
- The New FAQ Entry admin page rendered blank: its layout handle file is now named after the real action (`megventure_faq_entry_new`)

## 1.0.1 — 2026-09-02

### Fixed
- The /faq rewrite deleted other modules' rewrites. It was stored under the entity type Magento shares between modules, and Magento's rewrite save removes every row matching the entity type, entity id and store it writes — so installing this module deleted any rewrite another module had put there. Our own AI Visibility Toolkit lost its /llms.txt this way, silently: the URL simply started answering 404. The rewrite now lives under a type only this module writes, and upgrading moves the existing row across
