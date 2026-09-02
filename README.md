# Megventure_Faq — Product FAQ for Magento 2

Questions and answers on your product pages, and on one page of their own at
`/faq`. Written for shops that would rather answer a question on the page than
in an email.

Free and open source (MIT), by [megventure.com](https://megventure.com).

## What it does

- **Shared entries** (product ID 0) appear on every product page — delivery,
  returns, payment: the questions that are the same whatever the customer is
  looking at.
- **Product entries** appear on that one product page only, above the shared
  ones.
- **A FAQ page** at `/faq` lists everything you have published.
- **Per store view text.** Each entry carries one question and answer per store
  view, with the default store's text as a fallback, so a shop in eight
  languages does not need eight sets of entries.

Everything is rendered server-side, in plain `<details>` elements with no
JavaScript. That matters twice over: a crawler that does not run scripts still
reads the answers, and so does an assistant summarising your page.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.x
- PHP 8.1–8.4

## Installing

From the zip:

```bash
cd <magento root>
mkdir -p app/code/Megventure/Faq
unzip megventure-faq-<version>.zip -d app/code/Megventure/Faq
bin/magento module:enable Megventure_Faq
bin/magento setup:upgrade
bin/magento cache:flush
```

In production mode, also run `bin/magento setup:di:compile` and
`bin/magento setup:static-content:deploy`.

## Using it

**Content → Product FAQ** lists your entries. Add one, write the question and
the answer, and leave Product ID at 0 unless the entry belongs to a single
product.

**Stores → Configuration → Megventure → Product FAQ** controls where the
entries appear, the heading above them, whether the first one starts open, and
whether a store view without its own text falls back to the default.

The `/faq` page is created as a URL rewrite at install. If your shop already
has a CMS page on `/faq`, the rewrite is skipped and your page is left alone —
turn the FAQ page off in the configuration, or free the URL, if you want ours.

## Structured data

This module deliberately emits no `FAQPage` markup. Our
[AI Visibility Toolkit](https://megventure.com/en/magento-modules/) reads these
entries and publishes the schema alongside the rest of a shop's structured
data, which keeps one module in charge of what search engines and assistants
are told. Install both and it happens on its own; install this one alone and
you still get the content, just without the markup.

Note that Google retired FAQ rich results in May 2026. The markup is still
valid schema and is still read by other consumers — it just no longer wins you
stars in Google's results.

## Uninstalling

```bash
bin/magento module:disable Megventure_Faq
bin/magento setup:upgrade
```

Your entries stay in the database. To remove them as well, run
`bin/magento module:uninstall Megventure_Faq --remove-data`.

## Support

info@megventure.com — or open an issue on the repository.

## Licence

MIT. See LICENSE.txt.
