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
4. **Design tab:**
   - Layout: "1 column" (this is what keeps the real header/topmenu/footer
     rendering — do not pick a blank/empty layout)
   - Layout Update XML: paste the entire contents of `layout-update.xml`
     (same folder as this file) into this field — this is what loads
     `landing-grumman-llv.css` on this page only
5. **Meta Data tab:**
   - Meta Title: `USPS Grumman LLV PCM Repair | Solo Auto Electronics`
   - Meta Description: `Grumman LLV PCM testing and repair for no-start, stalling and communication problems. Work directly with an experienced Solo Auto Electronics specialist.`
6. **Save Page.**
7. Do **not** add this page to the main navigation menu — it's meant to be
   a standalone link/SEO landing page, not a nav item.
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

If anything looks off style-wise, the fix is almost always in
`skin/frontend/rwd/default/css/landing-grumman-llv.css` — every rule in
that file is scoped under `.landing-grumman-llv`, so it's safe to edit
without risk of touching any other page.
