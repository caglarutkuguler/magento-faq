# Product FAQ for Magento 2 — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 1.1.1 — 2026-11-24

### Fixed
- A store view added after installation (a second storefront on another theme) answered 404 on /faq. The address is now published for new store views as they are created

## 1.1.0 — 2026-11-24

### New
- Hyvä compatibility: FAQ page uses a one-column layout; heading, answer and link styles restated for Hyvä

## 1.0.2 — 2026-11-23

### Fixed
- The New FAQ Entry admin page rendered blank: its layout handle file is now named after the real action (`megventure_faq_entry_new`)
