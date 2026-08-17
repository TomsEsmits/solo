# Admin steps: USPS Grumman LLV PCM Repair landing page

Everything code-side is already committed to the repo (CSS in
`skin/frontend/rwd/default/css/landing-grumman-llv.css`, images in
`skin/frontend/rwd/default/images/landing/grumman-llv/`). These files only
take effect once this branch is deployed to wherever `solopcms.test` /
`solopcms.com` actually serves from — deploy them the same way you deploy
any other theme change.

The CMS page itself has to be created by hand in Magento admin, since this
session has no admin login. Steps:

1. **CMS > Pages > Add New Page**
2. **Page Information tab:**
   - Page Title: `USPS Grumman LLV PCM & ECM Repair`
   - URL Key: `usps-grumman-llv-pcm-repair`
   - Store View: select your live store view (leave "All Store Views" only
     if that's your normal practice for other pages)
   - Status: Enabled
3. **Content tab:**
   - Click "Show / Hide Editor" to switch to raw HTML mode
   - Paste the entire contents of `cms-page-content.html` (same folder as
     this file) into the Content box
   - Leave the **Content Heading** field (a separate field from the main
     Content box, above it) **empty**. If you fill it in, Magento renders
     an extra `<h1>` page title above the pasted content, which would
     duplicate the page's own headline (already in the pasted HTML) and
     hurt SEO on a page whose whole purpose is SEO.
   - **Warning:** once this HTML is pasted in raw/HTML mode and saved, do
     **not** re-open this page in the WYSIWYG (rich text) editor and save
     again. Magento's editor doesn't recognize `<svg>` markup and will
     strip all the inline SVG icons on the page, and it can also mangle
     the `{{skin url=...}}` image directives. Any future edits to this
     page's content must also be done in raw HTML mode.
4. **Design tab:**
   - Layout: "1 column" (this is what keeps the real header/topmenu/footer
     rendering — do not pick a blank/empty layout)
   - Magento 1.9 has **two** different layout-XML fields on this tab: the
     plain **Layout Update XML** field, and a separate **Custom Layout
     Update XML** field inside a date-ranged "Custom Design Update"
     section. Use the plain **Layout Update XML** field — paste the entire
     contents of `layout-update.xml` (same folder as this file) into it.
     Do **not** use "Custom Layout Update XML" — picking the wrong field
     means the page renders completely unstyled with no error message.
     This is what loads `landing-grumman-llv.css` (and the body class that
     suppresses the hero's horizontal scrollbar) on this page only.
5. **Meta Data tab:**
   - Meta Title: `USPS Grumman LLV PCM Repair | Solo Auto Electronics`
   - Meta Description: `Grumman LLV PCM testing and repair for no-start, stalling and communication problems. Work directly with an experienced Solo Auto Electronics specialist.`
6. **Save Page.**
7. Do **not** add this page to the main navigation menu — it's meant to be
   a standalone link/SEO landing page, not a nav item.
7a. **Flush the cache:** go to **System > Cache Management** and click
    "Flush Magento Cache" before checking the live page. Otherwise the new
    CSS may not appear yet and it can look like something's broken when it
    isn't.
8. **Verify:**
   - Visit `/usps-grumman-llv-pcm-repair` on the site
   - Confirm the header, top nav, and footer look and work exactly like
     any other page (nav links work, "Quick Quote" header button still
     opens the sidebar, cart/account links unaffected)
   - Confirm the "Email Us About a Grumman LLV PCM" buttons on the new
     page open the same quick-quote sidebar (they use the site's existing
     `.quick-simple` trigger, no new form)
   - Confirm the "Call" buttons dial `(888) 848-0144`
   - Resize the browser down to a phone width and confirm the page
     reflows sensibly (single-column sections, stacked buttons)
   - Check for a horizontal scrollbar at the bottom of the browser window
     on a desktop-width view (Windows Chrome/Edge especially) — there
     shouldn't be one
   - Confirm the hero headline is white and the hero paragraph text is
     light/readable against the dark photo (not dark grey or blue)

If anything looks off style-wise, the fix is almost always in
`skin/frontend/rwd/default/css/landing-grumman-llv.css` — every rule in
that file is scoped under `.landing-grumman-llv`, so it's safe to edit
without risk of touching any other page.
