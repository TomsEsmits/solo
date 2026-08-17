# USPS Grumman LLV PCM Repair Landing Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Produce all files needed for a new, standalone "USPS Grumman LLV PCM & ECM Repair" CMS landing page on the solopcms.com Magento 1.9 storefront, without touching the real site header/topmenu/footer or any shared CSS/PHP.

**Architecture:** A scoped stylesheet (`landing-grumman-llv.css`) and four extracted photo assets are added to the `rwd/default` theme's `skin/` directory. A single self-contained HTML content block (root class `.landing-grumman-llv`) and a small Layout Update XML snippet are prepared as paste-in deliverables for a Magento CMS Page, since this session has no Magento admin credentials. The CMS page uses the theme's default 1-column layout, so the real `header.phtml` / `topmenu.phtml` / `footer.phtml` continue to render unmodified.

**Tech Stack:** Magento 1.9 CE CMS page + Layout Update XML, plain CSS (no SCSS build step used for this page), static image assets. No PHP, no new module, no JS beyond what the theme already loads globally (jQuery + sidr, already present).

## Global Constraints

- **Hard requirement:** header, topmenu, and footer styling/functionality must not be affected. Every CSS rule added by this feature must be nested under the `.landing-grumman-llv` selector — no bare `*`, `body`, `a`, `h1`–`h6`, `.btn`, `.container`, etc. (from spec `docs/superpowers/specs/2026-08-17-grumman-llv-landing-page-design.md`).
- The new stylesheet is loaded **only** on this one CMS page via that page's own Layout Update XML — never added to a global layout handle.
- This is a one-off page: no shared/reusable section partials, no new PHP/module/controller/route, no nav changes.
- URL key: `usps-grumman-llv-pcm-repair`. Page title / SEO title: "USPS Grumman LLV PCM & ECM Repair". Meta title: "USPS Grumman LLV PCM Repair | Solo Auto Electronics". Meta description: "Grumman LLV PCM testing and repair for no-start, stalling and communication problems. Work directly with an experienced Solo Auto Electronics specialist." Not linked in main nav.
- "Call" CTAs → `tel:+18888480144`. "Email"/quote CTAs → trigger the site's existing global quick-quote sidebar (class `quick-simple`, already wired in `footer.phtml` via `page/forms/quick-quote.phtml`) instead of `mailto:` — except plain informational contact-detail text (e.g. "Email: sales@solopcms.com" inside the info panel), which stays a real `mailto:` link since it isn't a lead-gen CTA.
- Legal disclaimer line must be preserved verbatim: "Solo Auto Electronics is an independent automotive electronics repair provider and is not affiliated with or endorsed by the United States Postal Service or Grumman."
- Source mockup already extracted to `/tmp/claude-1000/-home-mr-esh/d7378aa7-de48-4baa-9eab-ef0c091cf9f7/scratchpad/extracted/` in the brainstorming session (images `img_3_*.jpeg` 298562 bytes, `img_4_*.png` 658912 bytes, `img_5_*.jpeg` 467291 bytes, `img_6_*.jpeg` 31013 bytes are the four unique real photos needed; everything else extracted there — logo, header/clock/phone icons, duplicate mobile-view copies — is not used). If that scratchpad no longer exists when this plan is executed, re-extract from `USPS Grumman LLV PCM Repair - Solo Auto Electronics (2) (1).html` in the user's Downloads folder using the same technique (serve over local HTTP, open in browser, `fetch()` each image `blob:` URL and POST the bytes to a small local upload server — file:// URLs are blocked by the browser extension and direct raw-HTML reading won't work because the file is a self-extracting JS bundle).
- Real theme content width: `.main-container` (the ancestor of CMS page content) has `max-width: 1260px` with `15px`/`30px` side padding (`skin/frontend/rwd/default/css/styles.css:1800-1821`), not the mockup's 1594px canvas. Full-bleed sections use the `.llv-bleed` breakout technique (`width:100vw; margin-left:50%; transform:translateX(-50%)`). Without mitigation this can cause a page-wide horizontal scrollbar, since `100vw` always includes the vertical-scrollbar gutter and this theme's `overflow-x` is `visible` at every breakpoint (`skin/frontend/rwd/default/css/styles.css:15953`, `:16773` — `body>.wrapper { overflow-x: visible !important }`), i.e. no ancestor clips the overflow. The site owner was asked and chose to keep the full-bleed look rather than remove the technique, and approved a page-scoped fix: a body class (`usps-grumman-llv-pcm-repair`) added via this page's own Layout Update XML, paired with a `body.usps-grumman-llv-pcm-repair > .wrapper { overflow-x: hidden !important }` rule (documented as a sanctioned exception at the top of `landing-grumman-llv.css`).
- **Post-launch live-tweak round (2026-08-25):** once actually live at `https://www.solopcms.com/usps-grumman-llv-pcm-repair/`, the site owner requested visual adjustments after seeing the real production theme, which has diverged from what this plan was originally written against (production runs a newer `.new-header`/`.two-column-right-hero` header system not present in the locally-explored branch, and serves skin assets through a CloudFront CDN — `dn1qkewum0hvl.cloudfront.net` — so uploaded file changes may need a CDN cache purge/TTL wait to appear live). Changes made, all still scoped to this page only:
  - `.llv-inner` and `.llv-process__grid` `max-width` raised from `1200px` to `1590px` to match the real site header's content width.
  - `.llv-hero__bg`, `.llv-why__media img` width/height, and `.llv-strip img` height (`420px` → `520px`, mobile override `220px` kept) all needed `!important` — the live production theme apparently forces image dimensions elsewhere with higher specificity or `!important` of its own (not visible in the locally-explored branch, which had diverged from production); the site owner confirmed via direct testing in the browser which properties needed it.
  - Two more sanctioned exceptions added alongside the existing overflow-x one (all body-class-scoped, none can affect other pages): `body.usps-grumman-llv-pcm-repair .two-column-right-hero { display: none; }` hides the theme's auto-generated page-title banner above the content on this page only; `body.usps-grumman-llv-pcm-repair .main-content { padding-bottom: 0; border-bottom: 1px solid #CACACA; }` removes the theme's default spacing/rule under the main content area on this page only.
- **Second post-launch live-tweak round (2026-08-25, same session):** further visual refinements requested after seeing the 1590px-width update live:
  - `padding-bottom: 110px` moved from `.llv-hero` to `.llv-hero__scrim` (same value, different element — visual result is the badges-overlap spacing is now driven by the scrim, not the outer hero box).
  - `h1.llv-hero__title` color and `h2.llv-heading` color both given `!important` (same theme-specificity issue as the earlier Critical fix for these same two selectors — production's `.cms-page-view .std h1/h2` rules apparently still contest color here in some cases the earlier element-qualification fix didn't fully cover on the live theme). `h2.llv-heading` font-size/line-height also bumped `34px/1.15` → `42px/1.12` to match the live design more closely.
  - `.llv-specialist__media` width `460px` → `620px`; `.llv-specialist__media-backdrop` `300x220px` → `420x300px`; `.llv-specialist__media img` gained `width:100%`, `display:block`, `margin-top` `40px`→`60px`, updated `filter` drop-shadow values, and `height: unset !important` (overrides the earlier `.landing-grumman-llv img { height:auto }` base reset, which was fighting the intended natural aspect ratio here now that the image is width-constrained instead of absolutely positioned).
  - `.llv-point` padding `26px 28px` → `30px 32px`; `.llv-point__text` `16px/1.5` → `17px/1.55`.
  - `.llv-process__step` padding-top `20px` → `44px`; `.llv-process__num` `44px/16px-margin` → `52px/20px-margin`; `.llv-process__title` `20px/10px-margin` → `22px/14px-margin`.
  - `.llv-why__card-eyebrow` font-size `13px` → `14px`.
- **Third post-launch live-tweak round (2026-08-25, same session):**
  - `body.usps-grumman-llv-pcm-repair .main-content` gained `padding-top: 0` alongside its existing `padding-bottom: 0`/`border-bottom` (same sanctioned-exception rule, one more property).
  - `h1.llv-hero__title` font-size `48px` → `56px`; `.llv-eyebrow` font-size `14px` → `16px`.
  - **Responsive breakpoint changed sitewide-for-this-page: `900px` → `992px`.** The single `@media (max-width: 900px)` block (only occurrence of that breakpoint in the file) is now `@media (max-width: 992px)`, per the site owner's request to switch to the smaller-screen layout sooner rather than waiting until 900px.
- **Fourth post-launch live-tweak round (2026-08-25, same session):** `!important` added to every `font-size`/color-type declaration inside the `@media (max-width: 992px)` block, matching the treatment already applied to their desktop counterparts (`h1.llv-hero__title`, `h2.llv-heading` colors, image dimensions) — without it, a mobile-width override at normal specificity can lose to a `!important` desktop rule of the same selector regardless of the media query matching (this is the same class of bug caught and fixed for `.llv-strip img`'s mobile height earlier). No `color` properties are actually re-declared inside the media block (color values inherit from the `!important` desktop rules unchanged at every width, so there was nothing to touch there) — the three `font-size` declarations (`h1.llv-hero__title`, `p.llv-hero__lede`, the `h2.llv-heading` 3-selector compound) all gained `!important`.
- **Fifth post-launch live-tweak round (2026-08-25, same session):** a full mobile-typography pass across the `@media (max-width: 992px)` block, driven by two things: (1) explicit pixel values the site owner gave directly (`.llv-problems__grid` gains `width: 100%`; `.llv-hero` mobile `padding-bottom` moved to `0` and the freed-up spacing relocated to `.llv-hero__scrim`'s `padding-bottom: 80px`, keeping its existing `padding-top: 44px`; `.llv-badge__title` mobile `font-size: 15px !important` since the site owner observed it rendering at `12px` on small screens without an explicit override; `.llv-problems__item` mobile `padding: 16px 18px`; `.llv-problems__label` mobile `font-size: 15px`), and (2) the site owner asking to "compare font size on smaller screens for every section" against the original approved mobile design (the `#mobile-view` block from the source mockup, `USPS Grumman LLV PCM Repair - Solo Auto Electronics (2) (1).html`, extracted during brainstorming but never used verbatim — this plan deliberately collapsed it into responsive CSS on a single markup block instead of shipping duplicate desktop/mobile HTML). That comparison added mobile-only overrides, matched to the mockup's mobile values, for elements that previously had no mobile override at all and so silently inherited oversized desktop values: `.llv-eyebrow` (12px), `p.llv-lede` (15px/1.7), `p.llv-note` (14px/1.7), `.llv-badge__text` (12.5px), `.llv-specialist__media` padding-top (26px), `.llv-specialist__media-backdrop` (220×150px), `.llv-specialist__media img` margin-top (26px), `.llv-process` heading (added to the existing 26px compound selector — it wasn't covered before), `.llv-process__num` (34px), `.llv-process__title` (17px), `.llv-process__text` (14px), `.llv-why__media-caption strong` (17px), `.llv-why__card-eyebrow` (11px), `.llv-why__card-title` (18px), `.llv-why__card-text` (13.5px), `.llv-disclaimer` (12px). Known minor, deliberately-accepted discrepancy: the source mockup itself isn't fully internally consistent between sections for shared classes (e.g. `p.llv-lede` is 15px on mobile in the problems/specialist sections of the mockup but 14px in the quote section; `.llv-heading` mobile size varies 22–26px across sections) — this pass picked the value shared by the majority of sections for each shared class rather than forking per-section variants, to keep the CSS maintainable; not chased further unless the site owner flags a specific section as visibly wrong.
- **Sixth post-launch live-tweak round (2026-08-25, same session):** the site owner shared two side-by-side screenshots of the Common Problems section showing the mockup's original mobile content order (lede shortened to "...may include:", the note paragraph moved from before the grid to after it) versus what actually shipped (lede with the fuller "...may include the failures listed here." wording, note before the grid) — this is exactly the mobile-only content reordering the original design intentionally simplified away during brainstorming (see the source-mockup note in the Fifth round entry above), now being restored for this one section specifically because the site owner flagged it. Implementation, since flexbox can't reorder an element from inside one flex-child to after a sibling flex-child without either restructuring the DOM or forking the CSS layout entirely:
  - `p.llv-lede` copy shortened to "Common symptoms of a failing Grumman LLV PCM may include:" (applies at every breakpoint — pure copy edit, no layout implication).
  - The note paragraph is now **duplicated** in the HTML rather than reordered: the original stays inside `.llv-problems__intro` (before the grid) with an added class `llv-problems__note--desktop`; a second, identical copy was added as a new element after `.llv-problems__grid` (still inside `.llv-problems__layout`) with class `llv-problems__note--mobile`. CSS shows exactly one of the two at a time: `.llv-problems__note--mobile` is `display: none` by default (hidden on desktop) and `display: block` inside the `@media (max-width: 992px)` block; `.llv-problems__note--desktop` is shown by default and set `display: none` inside that same media block. A hidden (`display: none`) element is automatically excluded from the accessibility tree by browsers, so no `aria-hidden` is needed on either copy — whichever one is currently invisible is also correctly invisible to screen readers, with no risk of the note being announced twice or not at all.
  - This is a deliberate, scoped exception to the "don't ship duplicate desktop/mobile markup" principle from the original design — one paragraph, not the whole page, and only because visually reordering it across a flex-column-vs-flex-row breakpoint change has no CSS-only solution here without restructuring `.llv-problems__layout` in a way that risked the desktop 2-column layout the site owner hasn't asked to change. If more sections need this same before/after-the-grid reordering later, worth reconsidering a CSS Grid-based layout with named/ordered areas instead of piling up more duplicated paragraphs.
- **Seventh post-launch live-tweak round (2026-08-25, same session):** same class of request as the sixth round, this time for the Specialist section — the site owner shared screenshots showing the mockup's mobile order (text content, *then* the truck photo below it) versus what shipped (photo first, text below). Unlike the Common Problems note, this one needed no HTML change at all: `.llv-specialist__media` and `.llv-specialist__content` are already direct siblings in the same `.llv-specialist__layout` flex container (unlike the Problems note, which was nested two levels deep inside `.llv-problems__intro`), so a plain CSS `order` swap inside the `@media (max-width: 992px)` block is sufficient — `.llv-specialist__media { order: 2; }` and `.llv-specialist__content { order: 1; }`. Desktop is untouched (no `order` set there, so it keeps the DOM's natural media-then-content left/right arrangement). This is the general lesson from the sixth round's note: when the two things needing reordering are *already flex siblings*, `order` is the right tool and no duplication is needed; duplication is only a fallback for when the content to reorder is nested inside one of the siblings instead.
- **Eighth post-launch live-tweak round (2026-08-25, same session):** two more requests. (1) `.llv-point` mobile padding reduced from the inherited desktop `30px 32px` to `16px 18px`, matching the source mockup's mobile checklist-card padding (same value already used for `.llv-problems__item`). (2) The 3-point checklist and the photo strip needed to swap visual order on mobile (photo first, checklist below, overlapping its bottom edge) — but unlike the Specialist section (round seven), these two aren't flex siblings and the desktop overlap effect depends on DOM adjacency (`.llv-points` has `margin-bottom: -60px` pulling the *following* sibling, the strip section, up underneath it), so a plain `order` swap would have broken the overlap without also flipping which element carries the negative margin. Went with the same duplication technique as the Common Problems note (round six) instead: the strip `<section>` is now duplicated — one copy (`llv-strip--mobile`) placed *before* `.llv-inner > .llv-points`, one copy (`llv-strip--desktop`) kept in its original position *after* — with `display: none`/`block` toggling per breakpoint exactly one into view, same as the Problems note pattern. On mobile, `.llv-points` gets `margin-bottom: 0` (removing the now-irrelevant pull toward the hidden desktop copy, which would otherwise have yanked the *Process* section up instead) and a new `margin-top: -40px` (pulling itself up to overlap the *preceding* mobile strip image's bottom edge — the overlap direction had to flip along with the visual order). The `-40px` value is an estimate matching the screenshots' proportions, not a pixel-measured value from the mockup; nudge it if the overlap looks off once live.
- **Ninth post-launch live-tweak round (2026-08-25, same session):** two changes to the Process section steps on mobile, per screenshot comparison against the mockup. (1) `.llv-process__step` mobile `padding-top` set to `20px` (an explicit mobile-only override — desktop's `44px`, set in round two, was unintentionally inherited on mobile since no override previously existed; `20px` is the value that was in place before round two's desktop-only bump). (2) Restructured the step's number/title/text from vertical stacking (num above title above text, matching desktop) into a CSS Grid on mobile only: `grid-template-columns: auto 1fr` puts `.llv-process__num` and `.llv-process__title` side by side on row 1 (`align-items: baseline`, `column-gap: 16px`), with `.llv-process__text` spanning both columns (`grid-column: 1 / -1`) on row 2 below (`row-gap: 8px`). `margin-bottom` was zeroed on both num and title since the grid's `row-gap` now governs spacing instead. `.llv-process__tick` (the small cyan line marker) needed no change — it's `position: absolute`, so it's already removed from normal flow and unaffected by the switch from block stacking to grid. This restores, for this one section, the same mobile-vs-desktop number/title arrangement difference that the original source mockup had and that brainstorming deliberately dropped for simplicity (see the Fifth round's note on this file's general "same order both breakpoints" simplification) — same rationale as the sixth/eighth rounds' section-specific restorations.
- **Tenth post-launch live-tweak round (2026-08-25, same session):** further mobile refinements to the Why section's cards, requested directly (no screenshot this time, exact selectors/values given): (1) `.llv-why__media-caption` mobile padding set to `50px 20px 18px` (was inheriting desktop's `70px 30px 28px`, no prior mobile override). (2) `.llv-why__card` mobile padding set to `26px 24px` (was inheriting desktop's `36px 34px`) — this is the shared base class for all three card variants (`--dark`, `--outline`, `--cyan`), so one rule covers all three, matching how the site owner phrased it ("same for" the outline and cyan cards). (3) `.llv-why__card-icon` set to `display: none` on mobile. The request explicitly named this only next to `--dark`, but `.llv-why__card-icon` is itself the shared icon class used by all three variants (not a `--dark`-specific one), and the source mockup's mobile design omits the icon from all three card variants, not just the dark one — interpreted as hiding it everywhere for consistency with both the mockup and the shared-class structure; flagged to the site owner as an interpretation call in case only the dark card's icon was meant.
- **Eleventh post-launch live-tweak round (2026-08-25, same session):** the final-CTA/Quote section's mobile treatment, per screenshot comparison and explicit values:
  - `.llv-quote` mobile `padding-bottom: 40px` (new override; base is `0 0 90px`, i.e. `90px` bottom on desktop).
  - `.llv-quote__box` mobile padding `32px 26px` → `28px 24px`.
  - `.llv-quote__content h2.llv-heading` split out of the shared 26px mobile heading-size compound selector into its own rule at `22px !important` — this section's heading was always meant to be smaller on mobile than Problems/Specialist/Process per the source mockup (see the Fifth round's noted discrepancy), now fixed for this section specifically rather than picking the majority value.
  - `.llv-btn--cyan` and `.llv-btn--outline-blue` mobile: `font-size: 15px; padding: 15px;` (both CTA button variants used in this section's button row).
  - `.llv-quote__panel` mobile: `background: none; box-shadow: none; border-radius: 0; padding: 0;` — the info panel becomes plain text below the buttons instead of a bordered card, matching the screenshot.
  - `.llv-quote__panel-title` ("We are at your service") hidden on mobile.
  - `.llv-quote__panel-row` mobile: `font-size: 13px`; the `margin-top: 14px` between sibling rows removed (`margin-top: 0`) so spacing between rows comes only from line-height, matching the screenshot's uniform, tightly-flowing look.
  - `.llv-quote__panel-row a` (the phone/email links) mobile color changed to `#2B79C2` (was the shared navy link color).
  - Three specific `<br>` tags — the one between "Working hours:" and its value, and the two inside the "Ship to:" address — gained a new class `llv-quote__br--stack`, hidden (`display: none`) on mobile only. Desktop keeps these as real line breaks (unchanged, matching the originally-approved desktop design); mobile now lets "Working hours: Mon - Fri: 7:30am - 5:30pm" and the full ship-to address each flow as one line that wraps naturally, matching the mockup's mobile version of this panel (a single flowing text block, not stacked labels) without touching desktop's markup or requiring content duplication — a `<br class="...">` toggle is simpler than the duplication technique used for the Problems-note/photo-strip reorders (rounds six/eight) because here the fix is purely about suppressing specific line breaks, not moving an element relative to a sibling.
  - The Phone/Email `<br>` (between those two lines) was deliberately left as a real, undedicated `<br>` at both breakpoints — it wasn't flagged as a problem and already reads as two separate lines correctly.
- **Twelfth post-launch live-tweak round (2026-08-25, same session):** `.llv-disclaimer` mobile: `padding: 18px 22px` (was inheriting desktop's `24px` all-around) and `text-align: left` (was inheriting the base rule's `text-align: center`).
- **Thirteenth post-launch live-tweak round (2026-08-25, same session):** three small mobile spacing adjustments. (1) `.llv-inner` mobile gains `margin-bottom: 26px` — this is the universal content-wrapper class reused across every section, so this adds trailing space after each section's `.llv-inner` block uniformly; checked against the existing negative-margin overlap tricks (round eight's `.llv-points` mobile `margin-top: -40px`, the desktop `.llv-points`/`.llv-badges` overlaps) and confirmed safe — those all use `margin-top` on the overlapping element itself, unaffected by adding `margin-bottom` to a separate ancestor `.llv-inner`. (2) `.llv-problems` mobile `padding-bottom: 40px` (was inheriting `50px` from the shared `.llv-problems, .llv-specialist, .llv-process, .llv-why { padding: 50px 0; }` mobile rule — added as its own single-property override, same specificity, later in source, so only `padding-bottom` is overridden and the shared rule's other three sides are untouched for this selector). (3) `.llv-specialist` mobile `padding-top: 10px`, using the same override technique.
- **Fourteenth post-launch live-tweak round (2026-08-25, same session):** `.llv-problems__layout` mobile `gap` reduced from the shared `34px` (also used by `.llv-specialist__layout`) to `20px`, via the same single-property-override technique as the thirteenth round.
- **Fifteenth post-launch live-tweak round (2026-08-25, same session):** the Common Problems section's lede paragraph — specifically "Common symptoms of a failing Grumman LLV PCM may include:" — gets `margin-bottom: 0`, via `.landing-grumman-llv .llv-problems__intro p.llv-lede`. Unlike the other rounds in this batch, no breakpoint was specified for this one, so it was applied as a base rule (both desktop and mobile), scoped narrowly to this one section's lede rather than the shared `p.llv-lede` class (which is also used, with different desired spacing, in the Specialist and Quote sections).
- **Sixteenth post-launch live-tweak round (2026-08-25, same session):** `.llv-point__text` mobile `font-size: 14px` (new mobile-only override; base/desktop stays `17px`).
- **Seventeenth post-launch live-tweak round (2026-08-25, same session):** two more mobile spacing overrides using the established single-property-override technique — `.llv-why__cta` mobile `margin-top: 24px` (base is `44px`), `.llv-why` mobile `padding-bottom: 40px` (was inheriting the shared `50px` from the `.llv-problems, .llv-specialist, .llv-process, .llv-why` mobile rule, same pattern as rounds thirteen/fourteen).
- **Eighteenth post-launch live-tweak round (2026-08-25, same session):** a new (fifth) sanctioned exception — `body.usps-grumman-llv-pcm-repair .footer-inner-wrapper .footer-bottom-part .copyright { display: none; }` hides the shared site footer's copyright/description line (`<address class="copyright ...">Solo Auto Electronics has been serving...</address>`, from `footer.phtml`) on this page only. Same pattern as the other footer/header-adjacent exceptions: gated on the page-only body class, so no other page loses this text.
- **Nineteenth post-launch live-tweak round (2026-08-25, same session):** `.llv-hero__scrim` mobile `padding-bottom` reduced from `80px` (set in round eleven's padding-move-from-`.llv-hero`) to `35px`; `padding-top: 44px` unchanged.
- **Twentieth post-launch live-tweak round (2026-08-25, same session):** `.llv-problems` mobile gains `padding-top: 14px` (alongside its existing `padding-bottom: 40px` override from round twenty).
- **Twenty-first post-launch live-tweak round (2026-08-25, same session):** `p.llv-note` mobile gains `margin-bottom: 10px` (base rule is `margin: 0` on all sides; this overrides just the bottom for mobile).
- **Twenty-second post-launch live-tweak round (2026-08-25, same session):** `.llv-inner` mobile `margin-bottom` reverted from `26px` (round thirteen) back to `0` — the site owner tried the uniform trailing space across every section and asked to remove it again. `.llv-problems__layout` mobile also picked up its own explicit `margin-bottom: 0` in the same round (requested moments before this reversion, when `.llv-inner`'s `26px` was still in effect) — now redundant with `.llv-inner` back at `0`, but left in place since it's harmless and wasn't asked to be removed.
- **Twenty-third post-launch live-tweak round (2026-08-25, same session):** `.llv-hero__scrim` mobile `padding-bottom` changed again, `35px` (round nineteen) → `65px`.
- **Twenty-fourth post-launch live-tweak round (2026-08-25, same session):** `.llv-problems` mobile `padding-top` changed again, `14px` (round twenty) → `40px` (`padding-bottom: 40px` unchanged).
- Fonts (Rubik, Inter) are already loaded site-wide via `footer.phtml:109` (Google Fonts). Do not add a new font-face or font import.

---

### Task 1: Extract and prepare image assets

**Files:**
- Create: `skin/frontend/rwd/default/images/landing/grumman-llv/hero-engine-bay.jpg`
- Create: `skin/frontend/rwd/default/images/landing/grumman-llv/specialist-photo.png`
- Create: `skin/frontend/rwd/default/images/landing/grumman-llv/route-strip.jpg`
- Create: `skin/frontend/rwd/default/images/landing/grumman-llv/why-media.jpg`

**Interfaces:**
- Consumes: nothing (first task)
- Produces: four image files at the paths above, referenced by Task 3's HTML via `{{skin url='images/landing/grumman-llv/<filename>'}}`

- [ ] **Step 1: Copy the four unique photos from the extraction scratchpad into the theme**

```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
mkdir -p skin/frontend/rwd/default/images/landing/grumman-llv
SRC=/tmp/claude-1000/-home-mr-esh/d7378aa7-de48-4baa-9eab-ef0c091cf9f7/scratchpad/extracted
cp "$SRC/img_3_usps-grumman-llv-with-a-no-start-conditi.jpeg" skin/frontend/rwd/default/images/landing/grumman-llv/hero-engine-bay.jpg
cp "$SRC/img_4_usps-grumman-llv-with-a-no-start-conditi.png"  skin/frontend/rwd/default/images/landing/grumman-llv/specialist-photo.png
cp "$SRC/img_5_usps-mail-truck-on-route-in-miami.jpeg"        skin/frontend/rwd/default/images/landing/grumman-llv/route-strip.jpg
cp "$SRC/img_6_usps-grumman-llv-mail-truck-on-route.jpeg"     skin/frontend/rwd/default/images/landing/grumman-llv/why-media.jpg
```

If the scratchpad path no longer exists, re-extract per the Global Constraints note above, then re-run this copy step with the new source paths.

- [ ] **Step 2: Verify each file is a valid, correctly-sized image**

Run:
```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html/skin/frontend/rwd/default/images/landing/grumman-llv
file hero-engine-bay.jpg specialist-photo.png route-strip.jpg why-media.jpg
```
Expected: `hero-engine-bay.jpg: JPEG image data, ... 2000x857`, `specialist-photo.png: PNG image data, ... 1200 x 563`, `route-strip.jpg: JPEG image data, ... 2000x1125`, `why-media.jpg: JPEG image data, ... 678x452`. If any file is 0 bytes or the wrong type, the copy failed — re-check the source path.

- [ ] **Step 3: Commit**

```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
git add skin/frontend/rwd/default/images/landing/grumman-llv/
git commit -m "$(cat <<'EOF'
Add photo assets for USPS Grumman LLV PCM repair landing page

Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
EOF
)"
```

---

### Task 2: Write the scoped stylesheet

**Files:**
- Create: `skin/frontend/rwd/default/css/landing-grumman-llv.css`

**Interfaces:**
- Consumes: nothing
- Produces: CSS classes consumed by Task 3's HTML — root `.landing-grumman-llv`, plus `.llv-inner`, `.llv-eyebrow`, `.llv-heading` (+ `--center` modifier), `.llv-lede`, `.llv-note`, `.llv-btn` (+ `--cyan`/`--outline-white`/`--outline-blue` modifiers), `.llv-ctas`, `.llv-bleed`, `.llv-hero` (+ `__bg`, `__scrim`, `__title`, `__title-accent`, `__lede`), `.llv-badges`/`.llv-badge` (+ `__icon`/`__title`/`__text`), `.llv-problems` (+ `__layout`/`__intro`/`__grid`/`__item`/`__num`/`__label`), `.llv-specialist` (+ `__layout`/`__media`/`__media-backdrop`/`__content`), `.llv-points`/`.llv-point` (+ `__text`), `.llv-strip`, `.llv-process` (+ `__grid`/`__step`/`__tick`/`__num`/`__title`/`__text`), `.llv-why` (+ `__grid`/`__media`/`__media-caption`/`__card` with `--dark`/`--outline`/`--cyan` modifiers, `__card-icon`/`__card-eyebrow`/`__card-title`/`__card-text`/`__cta`), `.llv-quote` (+ `__box`/`__content`/`__panel`/`__panel-title`/`__panel-row`), `.llv-disclaimer`.

- [ ] **Step 1: Write the stylesheet**

Create `skin/frontend/rwd/default/css/landing-grumman-llv.css`:

```css
/* Scoped styles for the USPS Grumman LLV PCM Repair landing page.
   Every selector is nested under .landing-grumman-llv so nothing here
   can affect the site header, topmenu, or footer. Loaded only on this
   one CMS page via that page's Layout Update XML — never add this file
   to a global layout handle. */

/* SANCTIONED EXCEPTIONS — the only rules in this file not nested under
   .landing-grumman-llv. Every one is additionally scoped to
   body.usps-grumman-llv-pcm-repair, a class that ONLY exists on this one
   CMS page (added by this page's own Layout Update XML — see
   docs/superpowers/plans/grumman-llv-admin-handoff/layout-update.xml), so
   none of these can affect any other page on the site even though the
   selectors themselves reach outside .landing-grumman-llv. */

/* .llv-bleed uses 100vw to break out of the theme's container for true
   edge-to-edge hero/photo-strip backgrounds. 100vw always includes the
   vertical-scrollbar gutter, and this theme's overflow-x is `visible` at
   every breakpoint (skin/frontend/rwd/default/css/styles.css:15953,16773),
   so without this rule the hero causes a page-wide horizontal scrollbar. */
body.usps-grumman-llv-pcm-repair > .wrapper {
  overflow-x: hidden !important;
}

/* The theme's own "page title" banner above the main content — approved
   by site owner 2026-08-25 to be hidden on this page only. */
body.usps-grumman-llv-pcm-repair .two-column-right-hero {
  display: none;
}

/* Removes the theme's default bottom spacing/rule under the main content
   area on this page only — approved by site owner 2026-08-25. */
body.usps-grumman-llv-pcm-repair .main-content {
  padding-top: 0;
  padding-bottom: 0;
  border-bottom: 1px solid #CACACA;
}

/* Hides the shared footer's copyright/description line on this page
   only — approved by site owner 2026-08-25. */
body.usps-grumman-llv-pcm-repair .footer-inner-wrapper .footer-bottom-part .copyright {
  display: none;
}

.landing-grumman-llv {
  --llv-navy: #39608E;
  --llv-cyan: #19BAE0;
  --llv-cyan-hover: #14A5C7;
  --llv-dark: #141B24;
  --llv-slate: #3B4652;
  --llv-body: #5A5A5A;
  --llv-muted: #909090;
  --llv-amber: #F5AD0D;
  --llv-border: #E9E9E9;
  --llv-panel-bg: rgba(243, 244, 246, 0.6);
  --llv-radius-lg: 24px;
  --llv-radius-md: 16px;
  --llv-shadow-card: 0px 20px 60px rgba(141, 141, 141, 0.16), 0px 6px 20px rgba(141, 141, 141, 0.08);

  font-family: 'Rubik', -apple-system, 'Segoe UI', sans-serif;
  color: var(--llv-body);
  font-size: 16px;
  line-height: 1.5;
}

.landing-grumman-llv * {
  box-sizing: border-box;
}

.landing-grumman-llv img {
  max-width: 100%;
  height: auto;
  display: block;
}

.landing-grumman-llv .llv-inner {
  max-width: 1590px;
  margin: 0 auto;
  padding: 0 24px;
}

.landing-grumman-llv .llv-eyebrow {
  font-weight: 600;
  font-size: 16px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--llv-cyan);
  margin: 0 0 18px;
}

.landing-grumman-llv h2.llv-heading {
  font-weight: 600;
  font-size: 42px;
  line-height: 1.12;
  color: var(--llv-navy) !important;
  margin: 0 0 20px;
}

.landing-grumman-llv .llv-heading--center {
  text-align: center;
}

.landing-grumman-llv p.llv-lede {
  font-size: 17px;
  line-height: 1.8;
  margin: 0 0 14px;
}

.landing-grumman-llv p.llv-note {
  font-size: 15px;
  line-height: 1.8;
  color: var(--llv-muted);
  margin: 0;
}

.landing-grumman-llv .llv-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-weight: 600;
  font-size: 16px;
  letter-spacing: 0.01em;
  padding: 18px 36px;
  border-radius: 50px;
  text-decoration: none;
  border: none;
  cursor: pointer;
  font-family: inherit;
}

.landing-grumman-llv .llv-btn:hover {
  text-decoration: none;
}

.landing-grumman-llv .llv-btn svg {
  flex: none;
}

.landing-grumman-llv .llv-btn--cyan {
  background: var(--llv-cyan);
  color: #FFFFFF;
}

.landing-grumman-llv .llv-btn--cyan:hover {
  background: var(--llv-cyan-hover);
  color: #FFFFFF;
}

.landing-grumman-llv .llv-btn--outline-white {
  box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.8);
  color: #FFFFFF;
  background: transparent;
}

.landing-grumman-llv .llv-btn--outline-white:hover {
  background: #FFFFFF;
  color: var(--llv-dark);
}

.landing-grumman-llv .llv-btn--outline-blue {
  box-shadow: inset 0 0 0 1px var(--llv-navy);
  color: var(--llv-navy);
  background: transparent;
}

.landing-grumman-llv .llv-btn--outline-blue:hover {
  background: var(--llv-navy);
  color: #FFFFFF;
}

.landing-grumman-llv .llv-ctas {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}

.landing-grumman-llv .llv-bleed {
  position: relative;
  width: 100vw;
  margin-left: 50%;
  transform: translateX(-50%);
}

.landing-grumman-llv .llv-hero {
  position: relative;
  background: var(--llv-dark);
  overflow: hidden;
}

.landing-grumman-llv .llv-hero__bg {
  position: absolute;
  inset: 0;
  width: 100% !important;
  height: 100% !important;
  object-fit: cover;
}

.landing-grumman-llv .llv-hero__scrim {
  position: relative;
  background: linear-gradient(100deg, rgba(15, 12, 8, 0.9) 0%, rgba(15, 12, 8, 0.6) 55%, rgba(15, 12, 8, 0.15) 100%);
  padding: 70px 0 0;
  padding-bottom: 110px;
}

.landing-grumman-llv h1.llv-hero__title {
  font-weight: 600;
  font-size: 56px;
  line-height: 1.1;
  color: #FFFFFF !important;
  margin: 0 0 26px;
  max-width: 720px;
}

.landing-grumman-llv .llv-hero__title-accent {
  color: var(--llv-cyan);
}

.landing-grumman-llv p.llv-hero__lede {
  font-size: 17px;
  line-height: 1.75;
  color: rgba(255, 255, 255, 0.85);
  margin: 0 0 34px;
  max-width: 680px;
}

.landing-grumman-llv .llv-badges {
  position: relative;
  z-index: 2;
  margin-top: -80px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

.landing-grumman-llv .llv-badge {
  background: #FFFFFF;
  border-radius: var(--llv-radius-lg);
  padding: 28px 26px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  box-shadow: var(--llv-shadow-card);
}

.landing-grumman-llv .llv-badge__icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(25, 186, 224, 0.12);
  display: flex;
  align-items: center;
  justify-content: center;
}

.landing-grumman-llv .llv-badge__title {
  font-weight: 600;
  font-size: 18px;
  color: var(--llv-navy);
}

.landing-grumman-llv .llv-badge__text {
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  line-height: 1.6;
  color: var(--llv-body);
}

.landing-grumman-llv .llv-problems {
  padding: 90px 0;
}

.landing-grumman-llv .llv-problems__layout {
  display: flex;
  gap: 70px;
  align-items: flex-start;
}

.landing-grumman-llv .llv-problems__intro {
  width: 420px;
  flex: none;
}

.landing-grumman-llv .llv-problems__intro h2.llv-heading {
  font-size: 36px;
}

.landing-grumman-llv .llv-problems__intro p.llv-lede {
  margin-bottom: 0;
}

.landing-grumman-llv .llv-problems__grid {
  flex: 1;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1px;
  background: var(--llv-border);
  border: 1px solid var(--llv-border);
}

.landing-grumman-llv .llv-problems__item {
  background: #FFFFFF;
  padding: 26px 28px;
  display: flex;
  gap: 14px;
  align-items: center;
}

.landing-grumman-llv .llv-problems__num {
  font-weight: 600;
  font-size: 14px;
  color: var(--llv-amber);
  flex: none;
}

.landing-grumman-llv .llv-problems__label {
  font-weight: 500;
  font-size: 17px;
  line-height: 1.4;
  color: var(--llv-slate);
}

.landing-grumman-llv .llv-problems__note--mobile {
  display: none;
}

.landing-grumman-llv .llv-specialist {
  padding: 90px 0 60px;
}

.landing-grumman-llv .llv-specialist__layout {
  display: flex;
  gap: 70px;
  align-items: center;
}

.landing-grumman-llv .llv-specialist__media {
  width: 620px;
  flex: none;
  position: relative;
  padding-top: 40px;
}

.landing-grumman-llv .llv-specialist__media-backdrop {
  position: absolute;
  right: 24px;
  top: 0;
  width: 420px;
  height: 300px;
  background: var(--llv-cyan);
}

.landing-grumman-llv .llv-specialist__media img {
  position: relative;
  width: 100%;
  display: block;
  margin-top: 60px;
  filter: drop-shadow(0 24px 40px rgba(20, 27, 36, 0.25));
  height: unset !important;
}

.landing-grumman-llv .llv-specialist__content {
  flex: 1;
}

.landing-grumman-llv .llv-specialist__content h2.llv-heading {
  font-size: 36px;
}

.landing-grumman-llv .llv-points {
  position: relative;
  z-index: 2;
  margin-bottom: -60px;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.landing-grumman-llv .llv-point {
  background: #FFFFFF;
  border-radius: var(--llv-radius-lg);
  padding: 30px 32px;
  display: flex;
  gap: 14px;
  align-items: center;
  box-shadow: var(--llv-shadow-card);
}

.landing-grumman-llv .llv-point svg {
  flex: none;
}

.landing-grumman-llv .llv-point__text {
  font-weight: 500;
  font-size: 17px;
  line-height: 1.55;
  color: var(--llv-slate);
}

.landing-grumman-llv .llv-strip--mobile {
  display: none;
}

.landing-grumman-llv .llv-strip img {
  width: 100%;
  height: 520px !important;
  object-fit: cover;
}

.landing-grumman-llv .llv-process {
  padding: 90px 0;
  background: var(--llv-panel-bg);
}

.landing-grumman-llv .llv-process__grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 30px;
  max-width: 1590px;
  margin: 0 auto;
}

.landing-grumman-llv .llv-process__step {
  position: relative;
  padding-top: 44px;
  border-top: 2px solid var(--llv-border);
}

.landing-grumman-llv .llv-process__tick {
  position: absolute;
  top: -2px;
  left: 0;
  width: 50px;
  height: 2px;
  background: var(--llv-cyan);
}

.landing-grumman-llv .llv-process__num {
  font-weight: 600;
  font-size: 52px;
  line-height: 1;
  color: var(--llv-cyan);
  margin-bottom: 20px;
}

.landing-grumman-llv .llv-process__title {
  font-weight: 600;
  font-size: 22px;
  line-height: 1.3;
  color: var(--llv-navy);
  margin-bottom: 14px;
}

.landing-grumman-llv .llv-process__text {
  font-size: 15px;
  line-height: 1.7;
  color: var(--llv-body);
}

.landing-grumman-llv .llv-why {
  padding: 90px 0;
}

.landing-grumman-llv .llv-why__grid {
  display: grid;
  grid-template-columns: 1.1fr 1fr 1fr;
  grid-template-rows: auto auto;
  gap: 24px;
}

.landing-grumman-llv .llv-why__media {
  grid-row: span 2;
  border-radius: var(--llv-radius-lg);
  overflow: hidden;
  position: relative;
  min-height: 420px;
}

.landing-grumman-llv .llv-why__media img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100% !important;
  object-fit: cover;
}

.landing-grumman-llv .llv-why__media-caption {
  position: absolute;
  inset: auto 0 0 0;
  background: linear-gradient(transparent, rgba(10, 15, 22, 0.85));
  padding: 70px 30px 28px;
  color: #FFFFFF;
}

.landing-grumman-llv .llv-why__media-caption .llv-eyebrow {
  margin-bottom: 8px;
}

.landing-grumman-llv .llv-why__media-caption strong {
  display: block;
  font-weight: 600;
  font-size: 22px;
  line-height: 1.3;
}

.landing-grumman-llv .llv-why__card {
  border-radius: var(--llv-radius-lg);
  padding: 36px 34px;
}

.landing-grumman-llv .llv-why__card--dark {
  grid-column: span 2;
  background: var(--llv-dark);
  color: #FFFFFF;
  display: flex;
  gap: 30px;
  align-items: flex-start;
}

.landing-grumman-llv .llv-why__card--outline {
  box-shadow: inset 0 0 0 1px var(--llv-border);
  background: #FFFFFF;
}

.landing-grumman-llv .llv-why__card--cyan {
  background: var(--llv-cyan);
  color: #FFFFFF;
}

.landing-grumman-llv .llv-why__card-icon {
  flex: none;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 18px;
}

.landing-grumman-llv .llv-why__card--dark .llv-why__card-icon {
  background: rgba(25, 186, 224, 0.15);
  margin-bottom: 0;
}

.landing-grumman-llv .llv-why__card--outline .llv-why__card-icon {
  background: rgba(25, 186, 224, 0.12);
}

.landing-grumman-llv .llv-why__card--cyan .llv-why__card-icon {
  background: rgba(255, 255, 255, 0.2);
}

.landing-grumman-llv .llv-why__card-eyebrow {
  font-weight: 600;
  font-size: 14px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--llv-cyan);
  margin-bottom: 12px;
}

.landing-grumman-llv .llv-why__card--cyan .llv-why__card-eyebrow {
  color: rgba(255, 255, 255, 0.85);
}

.landing-grumman-llv .llv-why__card-title {
  font-weight: 600;
  font-size: 21px;
  line-height: 1.3;
  margin-bottom: 12px;
}

.landing-grumman-llv .llv-why__card--outline .llv-why__card-title {
  color: var(--llv-navy);
}

.landing-grumman-llv p.llv-why__card-text {
  font-family: 'Inter', sans-serif;
  font-size: 14.5px;
  line-height: 1.7;
}

.landing-grumman-llv .llv-why__card--dark .llv-why__card-text {
  color: rgba(255, 255, 255, 0.75);
}

.landing-grumman-llv .llv-why__card--outline .llv-why__card-text {
  color: var(--llv-body);
}

.landing-grumman-llv .llv-why__card--cyan .llv-why__card-text {
  color: rgba(255, 255, 255, 0.92);
}

.landing-grumman-llv .llv-why__cta {
  display: flex;
  justify-content: center;
  margin-top: 44px;
}

.landing-grumman-llv .llv-quote {
  padding: 0 0 90px;
}

.landing-grumman-llv .llv-quote__box {
  background: var(--llv-panel-bg);
  border: 1px solid var(--llv-border);
  border-radius: var(--llv-radius-lg);
  padding: 60px;
  display: flex;
  gap: 60px;
  align-items: center;
}

.landing-grumman-llv .llv-quote__content {
  flex: 1.1;
}

.landing-grumman-llv .llv-quote__content h2.llv-heading {
  font-size: 32px;
}

.landing-grumman-llv .llv-quote__panel {
  flex: none;
  width: 340px;
  background: #FFFFFF;
  box-shadow: inset 0 0 0 1px var(--llv-border);
  border-radius: var(--llv-radius-md);
  padding: 32px 34px;
  font-family: 'Inter', sans-serif;
}

.landing-grumman-llv .llv-quote__panel-title {
  font-family: 'Rubik', sans-serif;
  font-weight: 600;
  font-size: 19px;
  color: var(--llv-slate);
  margin-bottom: 18px;
}

.landing-grumman-llv .llv-quote__panel-row {
  font-size: 14.5px;
  line-height: 1.8;
  color: var(--llv-body);
  margin-top: 0;
}

.landing-grumman-llv .llv-quote__panel-row + .llv-quote__panel-row {
  margin-top: 14px;
}

.landing-grumman-llv .llv-quote__panel-row strong {
  color: var(--llv-slate);
}

.landing-grumman-llv .llv-quote__panel-row a {
  color: var(--llv-navy);
  text-decoration: none;
}

.landing-grumman-llv .llv-quote__panel-row a:hover {
  text-decoration: underline;
}

.landing-grumman-llv .llv-disclaimer {
  background: var(--llv-panel-bg);
  padding: 24px;
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  line-height: 1.6;
  color: var(--llv-muted);
  font-style: italic;
  text-align: center;
}

@media (max-width: 992px) {
  .landing-grumman-llv .llv-inner {
    padding: 0 20px;
    margin-bottom: 0;
  }

  .landing-grumman-llv .llv-eyebrow {
    font-size: 12px;
  }

  .landing-grumman-llv p.llv-lede {
    font-size: 15px;
    line-height: 1.7;
  }

  .landing-grumman-llv p.llv-note {
    font-size: 14px;
    line-height: 1.7;
    margin-bottom: 10px;
  }

  .landing-grumman-llv .llv-hero {
    padding-bottom: 0;
  }

  .landing-grumman-llv .llv-hero__scrim {
    padding-top: 44px;
    padding-bottom: 65px;
  }

  .landing-grumman-llv h1.llv-hero__title {
    font-size: 30px !important;
    margin-bottom: 18px;
  }

  .landing-grumman-llv p.llv-hero__lede {
    font-size: 15px !important;
    margin-bottom: 26px;
  }

  .landing-grumman-llv .llv-ctas {
    flex-direction: column;
    align-items: stretch;
  }

  .landing-grumman-llv .llv-btn {
    text-align: center;
  }

  .landing-grumman-llv .llv-badges {
    margin-top: -40px;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .landing-grumman-llv .llv-badge {
    padding: 18px 16px;
    border-radius: var(--llv-radius-md);
  }

  .landing-grumman-llv .llv-badge__title {
    font-size: 15px !important;
  }

  .landing-grumman-llv .llv-badge__text {
    font-size: 12.5px;
  }

  .landing-grumman-llv .llv-problems,
  .landing-grumman-llv .llv-specialist,
  .landing-grumman-llv .llv-process,
  .landing-grumman-llv .llv-why {
    padding: 50px 0;
  }

  .landing-grumman-llv .llv-problems {
    padding-top: 40px;
    padding-bottom: 40px;
  }

  .landing-grumman-llv .llv-specialist {
    padding-top: 10px;
  }

  .landing-grumman-llv .llv-problems__layout,
  .landing-grumman-llv .llv-specialist__layout {
    flex-direction: column;
    gap: 34px;
  }

  .landing-grumman-llv .llv-problems__layout {
    gap: 20px;
    margin-bottom: 0;
  }

  .landing-grumman-llv .llv-problems__intro,
  .landing-grumman-llv .llv-specialist__media {
    width: 100%;
  }

  .landing-grumman-llv .llv-problems__intro h2.llv-heading,
  .landing-grumman-llv .llv-specialist__content h2.llv-heading,
  .landing-grumman-llv .llv-process h2.llv-heading {
    font-size: 26px !important;
  }

  .landing-grumman-llv .llv-quote__content h2.llv-heading {
    font-size: 22px !important;
  }

  .landing-grumman-llv .llv-problems__grid {
    grid-template-columns: 1fr;
    width: 100%;
  }

  .landing-grumman-llv .llv-problems__item {
    padding: 16px 18px;
  }

  .landing-grumman-llv .llv-problems__label {
    font-size: 15px;
  }

  .landing-grumman-llv .llv-problems__note--desktop {
    display: none;
  }

  .landing-grumman-llv .llv-problems__note--mobile {
    display: block;
  }

  .landing-grumman-llv .llv-points {
    grid-template-columns: 1fr;
    gap: 14px;
    margin-bottom: 0;
    margin-top: -40px;
  }

  .landing-grumman-llv .llv-point {
    padding: 16px 18px;
  }

  .landing-grumman-llv .llv-point__text {
    font-size: 14px;
  }

  .landing-grumman-llv .llv-strip img {
    height: 220px !important;
  }

  .landing-grumman-llv .llv-strip--mobile {
    display: block;
  }

  .landing-grumman-llv .llv-strip--desktop {
    display: none;
  }

  .landing-grumman-llv .llv-specialist__media {
    padding-top: 26px;
    order: 2;
  }

  .landing-grumman-llv .llv-specialist__content {
    order: 1;
  }

  .landing-grumman-llv .llv-specialist__media-backdrop {
    width: 220px;
    height: 150px;
  }

  .landing-grumman-llv .llv-specialist__media img {
    margin-top: 26px;
  }

  .landing-grumman-llv .llv-process__grid {
    grid-template-columns: 1fr;
    gap: 26px;
  }

  .landing-grumman-llv .llv-process__step {
    padding-top: 20px;
    display: grid;
    grid-template-columns: auto 1fr;
    column-gap: 16px;
    row-gap: 8px;
    align-items: baseline;
  }

  .landing-grumman-llv .llv-process__num {
    font-size: 34px;
    grid-column: 1;
    grid-row: 1;
    margin-bottom: 0;
  }

  .landing-grumman-llv .llv-process__title {
    font-size: 17px;
    grid-column: 2;
    grid-row: 1;
    margin-bottom: 0;
  }

  .landing-grumman-llv .llv-process__text {
    font-size: 14px;
    grid-column: 1 / -1;
    grid-row: 2;
  }

  .landing-grumman-llv .llv-why__grid {
    grid-template-columns: 1fr;
  }

  .landing-grumman-llv .llv-why__media {
    grid-row: auto;
    min-height: 240px;
  }

  .landing-grumman-llv .llv-why__media-caption {
    padding: 50px 20px 18px;
  }

  .landing-grumman-llv .llv-why__media-caption strong {
    font-size: 17px;
  }

  .landing-grumman-llv .llv-why__card {
    padding: 26px 24px;
  }

  .landing-grumman-llv .llv-why__card--dark {
    grid-column: auto;
    flex-direction: column;
  }

  .landing-grumman-llv .llv-why__card-icon {
    display: none;
  }

  .landing-grumman-llv .llv-why__cta {
    margin-top: 24px;
  }

  .landing-grumman-llv .llv-why {
    padding-bottom: 40px;
  }

  .landing-grumman-llv .llv-why__card-eyebrow {
    font-size: 11px;
  }

  .landing-grumman-llv .llv-why__card-title {
    font-size: 18px;
  }

  .landing-grumman-llv p.llv-why__card-text {
    font-size: 13.5px;
  }

  .landing-grumman-llv .llv-quote {
    padding-bottom: 40px;
  }

  .landing-grumman-llv .llv-btn--cyan,
  .landing-grumman-llv .llv-btn--outline-blue {
    font-size: 15px;
    padding: 15px;
  }

  .landing-grumman-llv .llv-quote__box {
    flex-direction: column;
    padding: 28px 24px;
    gap: 30px;
  }

  .landing-grumman-llv .llv-quote__panel {
    width: 100%;
    background: none;
    box-shadow: none;
    border-radius: 0;
    padding: 0;
  }

  .landing-grumman-llv .llv-quote__panel-title {
    display: none;
  }

  .landing-grumman-llv .llv-quote__panel-row {
    font-size: 13px;
  }

  .landing-grumman-llv .llv-quote__panel-row + .llv-quote__panel-row {
    margin-top: 0;
  }

  .landing-grumman-llv .llv-quote__panel-row a {
    color: #2B79C2;
  }

  .landing-grumman-llv .llv-quote__br--stack {
    display: none;
  }

  .landing-grumman-llv .llv-disclaimer {
    font-size: 12px;
    padding: 18px 22px;
    text-align: left;
  }
}
```

- [ ] **Step 2: Verify every rule is scoped under `.landing-grumman-llv`**

Run:
```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
python3 -c "
import re
css = open('skin/frontend/rwd/default/css/landing-grumman-llv.css', encoding='utf-8').read()
no_comments = re.sub(r'/\*.*?\*/', '', css, flags=re.S)
bad = []
for i, raw in enumerate(no_comments.splitlines(), 1):
    line = raw.strip()
    if not line:
        continue
    if line.startswith('@media') or line in ('}', ')') or line.endswith(');') or line.endswith(';'):
        continue
    if line.endswith('{') or line.endswith(','):
        if not line.startswith('.landing-grumman-llv'):
            bad.append((i, raw))
if bad:
    for ln, raw in bad:
        print(f'{ln}: {raw}')
else:
    print('all selectors scoped')
"
```
Expected (as originally written, before later rounds added the sanctioned exceptions below): `all selectors scoped`. Any printed `line: content` is a selector that isn't nested under `.landing-grumman-llv` — fix it before continuing (this is the check that directly enforces the "must not affect header/menu/footer" requirement). The script treats any line ending in `{` or `,` as a selector line and requires it to start with `.landing-grumman-llv`; lines ending in `;` or `);` are treated as property/value lines and skipped; `/* */` comments are stripped first so multi-line comment text can't cause false positives.

**Post-final-review update:** the committed CSS file now contains four intentional exceptions, all under the "SANCTIONED EXCEPTIONS" comment at the top of the CSS block above: `body.usps-grumman-llv-pcm-repair > .wrapper` (the `.llv-bleed` scrollbar fix, see Global Constraints), `body.usps-grumman-llv-pcm-repair .two-column-right-hero` (hides the theme's page-title banner on this page only), `body.usps-grumman-llv-pcm-repair .main-content` (removes the theme's default bottom spacing on this page only), and `body.usps-grumman-llv-pcm-repair .footer-inner-wrapper .footer-bottom-part .copyright` (hides the shared footer's copyright line on this page only) — all added across the 2026-08-25 post-launch live-tweak rounds, see Global Constraints. Running this script against the final file now prints those four lines instead of `all selectors scoped`, and that four-line output is the correct, expected result.

- [ ] **Step 3: Commit**

```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
git add skin/frontend/rwd/default/css/landing-grumman-llv.css
git commit -m "$(cat <<'EOF'
Add scoped stylesheet for USPS Grumman LLV PCM repair landing page

Every selector is nested under .landing-grumman-llv so this file cannot
affect the site header, topmenu, or footer, even though it is loaded on
a page that renders all three.

Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
EOF
)"
```

---

### Task 3: Write the CMS page content HTML deliverable

**Files:**
- Create: `docs/superpowers/plans/grumman-llv-admin-handoff/cms-page-content.html`

**Interfaces:**
- Consumes: CSS classes from Task 2 (`.landing-grumman-llv` and all `.llv-*` classes listed in Task 2's Interfaces); image filenames from Task 1 (`hero-engine-bay.jpg`, `specialist-photo.png`, `route-strip.jpg`, `why-media.jpg`)
- Produces: the exact HTML block the user will paste into the CMS page's Content field (Task 6 references this file's path)

- [ ] **Step 1: Write the content HTML**

Create `docs/superpowers/plans/grumman-llv-admin-handoff/cms-page-content.html`:

```html
<div class="landing-grumman-llv">

  <section class="llv-hero llv-bleed">
    <img class="llv-hero__bg" src="{{skin url='images/landing/grumman-llv/hero-engine-bay.jpg'}}" alt="USPS Grumman LLV with a no-start condition caused by a faulty PCM">
    <div class="llv-hero__scrim">
      <div class="llv-inner">
        <div class="llv-eyebrow">USPS Grumman LLV PCM &amp; ECM Repair</div>
        <h1 class="llv-hero__title">Grumman LLV PCM problems? Send it to a <span class="llv-hero__title-accent">dedicated specialist</span></h1>
        <p class="llv-hero__lede">A bad PCM can leave a Grumman LLV with a no-start condition, intermittent operation, or no communication with diagnostic equipment. Solo Auto Electronics provides professional testing and repair for Grumman LLV engine control modules used by USPS vehicle maintenance facilities and fleet repair operations. Our technicians have hands-on experience with these units and understand the importance of getting postal vehicles repaired and back into service.</p>
        <div class="llv-ctas">
          <a href="tel:+18888480144" class="llv-btn llv-btn--cyan">Call a Grumman LLV Specialist</a>
          <a href="#simple-menu" class="llv-btn llv-btn--outline-white quick-simple">Email Us About a Grumman LLV PCM</a>
        </div>
      </div>
    </div>
  </section>

  <div class="llv-inner">
    <div class="llv-badges">
      <div class="llv-badge">
        <div class="llv-badge__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#19BAE0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v8H3zM14 10h4l3 3v2h-7zM7 18a2 2 0 1 0 0-.01M17 18a2 2 0 1 0 0-.01"></path></svg></div>
        <div class="llv-badge__title">USPS VMFs</div>
        <div class="llv-badge__text">Served in Florida &amp; other states</div>
      </div>
      <div class="llv-badge">
        <div class="llv-badge__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#19BAE0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 4.5-3.4 7.8-8 9-4.6-1.2-8-4.5-8-9V6l8-3zM8.5 12l2.5 2.5 4.5-4.5"></path></svg></div>
        <div class="llv-badge__title">Since 2003</div>
        <div class="llv-badge__text">Auto electronics repair specialists</div>
      </div>
      <div class="llv-badge">
        <div class="llv-badge__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#19BAE0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4.5 4.5 0 0 0-6 6L4 17v3h3l4.7-4.7a4.5 4.5 0 0 0 6-6L14 13l-3-3 3.7-3.7z"></path></svg></div>
        <div class="llv-badge__title">Original module</div>
        <div class="llv-badge__text">Tested, rebuilt &amp; returned to you</div>
      </div>
      <div class="llv-badge">
        <div class="llv-badge__icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#19BAE0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5a3 3 0 1 0 0-.01M5 19a3 3 0 1 0 0-.01M19 19a3 3 0 1 0 0-.01M12 8v4M10 13l-3.5 4M14 13l3.5 4"></path></svg></div>
        <div class="llv-badge__title">Fleet-ready</div>
        <div class="llv-badge__text">One facility or many locations</div>
      </div>
    </div>
  </div>

  <section class="llv-problems">
    <div class="llv-inner llv-problems__layout">
      <div class="llv-problems__intro">
        <div class="llv-eyebrow">Common problems</div>
        <h2 class="llv-heading">Common Grumman LLV PCM Problems</h2>
        <p class="llv-lede">Common symptoms of a failing Grumman LLV PCM may include:</p>
        <p class="llv-note llv-problems__note--desktop">Because these symptoms can also be caused by wiring, sensors, power supply issues, or other components, the original PCM should be properly diagnosed before replacement.</p>
      </div>
      <div class="llv-problems__grid">
        <div class="llv-problems__item"><span class="llv-problems__num">01</span><span class="llv-problems__label">Engine cranks but will not start</span></div>
        <div class="llv-problems__item"><span class="llv-problems__num">02</span><span class="llv-problems__label">Intermittent no-start condition</span></div>
        <div class="llv-problems__item"><span class="llv-problems__num">03</span><span class="llv-problems__label">No communication with diagnostic equipment</span></div>
        <div class="llv-problems__item"><span class="llv-problems__num">04</span><span class="llv-problems__label">Fuel injector or ignition control problems</span></div>
        <div class="llv-problems__item"><span class="llv-problems__num">05</span><span class="llv-problems__label">Vehicle stalls or shuts off unexpectedly</span></div>
        <div class="llv-problems__item"><span class="llv-problems__num">06</span><span class="llv-problems__label">Check engine light or recurring fault codes</span></div>
      </div>
      <p class="llv-note llv-problems__note--mobile">Because these symptoms can also be caused by wiring, sensors, power supply issues, or other components, the original PCM should be properly diagnosed before replacement.</p>
    </div>
  </section>

  <section class="llv-specialist">
    <div class="llv-inner llv-specialist__layout">
      <div class="llv-specialist__media">
        <div class="llv-specialist__media-backdrop"></div>
        <img src="{{skin url='images/landing/grumman-llv/specialist-photo.png'}}" alt="Grumman LLV PCM module being evaluated by a Solo Auto Electronics specialist">
      </div>
      <div class="llv-specialist__content">
        <div class="llv-eyebrow">The specialist</div>
        <h2 class="llv-heading">A Dedicated Grumman LLV PCM Specialist</h2>
        <p class="llv-lede">Grumman LLV control modules require experience that many general automotive repair facilities may not have.</p>
        <p class="llv-note">Solo Auto Electronics has repaired these modules for USPS vehicle maintenance facilities in Florida and other states. That hands-on experience allows us to recognize recurring problems, properly evaluate the original module, and determine whether it can be repaired.</p>
        <p class="llv-note" style="margin-top:14px">Instead of searching for an often difficult-to-find replacement, send us the original PCM and let a dedicated specialist inspect it.</p>
      </div>
    </div>
  </section>

  <section class="llv-strip llv-strip--mobile llv-bleed">
    <img src="{{skin url='images/landing/grumman-llv/route-strip.jpg'}}" alt="USPS mail truck on route in Miami">
  </section>

  <div class="llv-inner">
    <div class="llv-points">
      <div class="llv-point">
        <svg width="26" height="26" viewBox="0 0 22 22"><circle cx="11" cy="11" r="11" fill="rgba(25,186,224,0.15)"></circle><path d="M6.5 11.5 L9.5 14.5 L15.5 8" fill="none" stroke="#19BAE0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span class="llv-point__text">Repaired modules for USPS vehicle maintenance facilities in Florida and other states</span>
      </div>
      <div class="llv-point">
        <svg width="26" height="26" viewBox="0 0 22 22"><circle cx="11" cy="11" r="11" fill="rgba(25,186,224,0.15)"></circle><path d="M6.5 11.5 L9.5 14.5 L15.5 8" fill="none" stroke="#19BAE0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span class="llv-point__text">Recognizes recurring Grumman LLV module problems</span>
      </div>
      <div class="llv-point">
        <svg width="26" height="26" viewBox="0 0 22 22"><circle cx="11" cy="11" r="11" fill="rgba(25,186,224,0.15)"></circle><path d="M6.5 11.5 L9.5 14.5 L15.5 8" fill="none" stroke="#19BAE0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
        <span class="llv-point__text">Evaluates the original module before any repair is approved</span>
      </div>
    </div>
  </div>

  <section class="llv-strip llv-strip--desktop llv-bleed">
    <img src="{{skin url='images/landing/grumman-llv/route-strip.jpg'}}" alt="USPS mail truck on route in Miami">
  </section>

  <section class="llv-process">
    <div class="llv-inner">
      <div class="llv-eyebrow llv-heading--center">The process</div>
      <h2 class="llv-heading llv-heading--center">How the Repair Process Works</h2>
      <div class="llv-process__grid">
        <div class="llv-process__step">
          <div class="llv-process__tick"></div>
          <div class="llv-process__num">01</div>
          <div class="llv-process__title">Contact Solo Auto Electronics</div>
          <div class="llv-process__text">Call or email us with the vehicle symptoms and any available diagnostic information.</div>
        </div>
        <div class="llv-process__step">
          <div class="llv-process__tick"></div>
          <div class="llv-process__num">02</div>
          <div class="llv-process__title">Ship Us the Original PCM</div>
          <div class="llv-process__text">Carefully package the module and include your contact information, return shipping address, vehicle information, and a description of the problem.</div>
        </div>
        <div class="llv-process__step">
          <div class="llv-process__tick"></div>
          <div class="llv-process__num">03</div>
          <div class="llv-process__title">Testing and Evaluation</div>
          <div class="llv-process__text">Our technicians will inspect and test the module to determine whether the reported condition is related to the PCM.</div>
        </div>
        <div class="llv-process__step">
          <div class="llv-process__tick"></div>
          <div class="llv-process__num">04</div>
          <div class="llv-process__title">Repair and Return</div>
          <div class="llv-process__text">If the module is repairable, we'll complete the repair and return the original unit to your facility for installation.</div>
        </div>
      </div>
    </div>
  </section>

  <section class="llv-why">
    <div class="llv-inner">
      <div class="llv-why__grid">
        <div class="llv-why__media">
          <img src="{{skin url='images/landing/grumman-llv/why-media.jpg'}}" alt="USPS Grumman LLV mail truck on route">
          <div class="llv-why__media-caption">
            <div class="llv-eyebrow">Keep them delivering</div>
            <strong>Repair the module. Return the route.</strong>
          </div>
        </div>
        <div class="llv-why__card llv-why__card--dark">
          <div class="llv-why__card-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#19BAE0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4.5 4.5 0 0 0-6 6L4 17v3h3l4.7-4.7a4.5 4.5 0 0 0 6-6L14 13l-3-3 3.7-3.7z"></path></svg></div>
          <div>
            <div class="llv-why__card-eyebrow">Why repair the original?</div>
            <div class="llv-why__card-title">Keep the module your vehicle already knows.</div>
            <p class="llv-why__card-text">Keeping the original PCM can help avoid the compatibility and availability problems associated with sourcing used or discontinued replacement modules.</p>
          </div>
        </div>
        <div class="llv-why__card llv-why__card--outline">
          <div class="llv-why__card-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#19BAE0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h13M4 12h9M4 17h13M20 10l-3 3-1.8-1.8"></path></svg></div>
          <div class="llv-why__card-eyebrow">Less setup on return</div>
          <div class="llv-why__card-title">May reduce programming and configuration needs.</div>
          <p class="llv-why__card-text">Repairing the original unit may also reduce the need for additional setup, programming, or vehicle-specific configuration. Every unit is evaluated individually before repair is approved.</p>
        </div>
        <div class="llv-why__card llv-why__card--cyan">
          <div class="llv-why__card-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v8H3zM14 10h4l3 3v2h-7zM7 18a2 2 0 1 0 0-.01M17 18a2 2 0 1 0 0-.01"></path></svg></div>
          <div class="llv-why__card-eyebrow">Built for USPS operations</div>
          <div class="llv-why__card-title">A dependable repair resource for one facility — or many.</div>
          <p class="llv-why__card-text">We work directly with vehicle maintenance facilities, fleet departments, and automotive repair professionals.</p>
        </div>
      </div>
      <div class="llv-why__cta">
        <a href="tel:+18888480144" class="llv-btn llv-btn--cyan">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          Call (888) 848-0144
        </a>
      </div>
    </div>
  </section>

  <section class="llv-quote">
    <div class="llv-inner">
      <div class="llv-quote__box">
        <div class="llv-quote__content">
          <h2 class="llv-heading">Have a Grumman LLV With a No-Start or PCM Problem?</h2>
          <p class="llv-lede" style="margin-bottom:8px">Talk directly with a Solo Auto Electronics specialist before replacing the module.</p>
          <p class="llv-lede" style="margin-bottom:28px">Send us the original Grumman LLV PCM for professional testing and repair.</p>
          <div class="llv-ctas">
            <a href="tel:+18888480144" class="llv-btn llv-btn--cyan">Call a Grumman LLV Specialist</a>
            <a href="#simple-menu" class="llv-btn llv-btn--outline-blue quick-simple">Email Us About a Grumman LLV PCM</a>
          </div>
        </div>
        <div class="llv-quote__panel">
          <div class="llv-quote__panel-title">We are at your service</div>
          <div class="llv-quote__panel-row"><strong>Working hours:</strong><br class="llv-quote__br--stack">Mon - Fri: 7:30am - 5:30pm</div>
          <div class="llv-quote__panel-row"><strong>Phone:</strong> <a href="tel:+18888480144">(888) 848-0144</a><br><strong>Email:</strong> <a href="mailto:sales@solopcms.com">sales@solopcms.com</a></div>
          <div class="llv-quote__panel-row"><strong>Ship to:</strong><br class="llv-quote__br--stack">14361 SW 120th Street Unit 106,<br class="llv-quote__br--stack">Miami, FL 33186</div>
        </div>
      </div>
    </div>
  </section>

  <div class="llv-disclaimer">Solo Auto Electronics is an independent automotive electronics repair provider and is not affiliated with or endorsed by the United States Postal Service or Grumman.</div>

</div>
```

- [ ] **Step 2: Verify every image reference matches a Task 1 filename**

Run:
```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
grep -o "images/landing/grumman-llv/[a-z-]*\.\(jpg\|png\)" docs/superpowers/plans/grumman-llv-admin-handoff/cms-page-content.html | sort -u
ls skin/frontend/rwd/default/images/landing/grumman-llv/
```
Expected: every path printed by the first command has a matching file in the second command's listing (`hero-engine-bay.jpg`, `specialist-photo.png`, `route-strip.jpg`, `why-media.jpg`).

- [ ] **Step 3: Verify no header/footer/nav markup leaked in**

Run:
```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
grep -inE "my cart|my account|<footer|<nav|solo pcms is a national|repair services</div>|all rights reserved" docs/superpowers/plans/grumman-llv-admin-handoff/cms-page-content.html
```
Expected: no output. Any match means the mockup's fake header/footer markup was accidentally copied in — remove it; the real `header.phtml`/`topmenu.phtml`/`footer.phtml` already provide all of this.

- [ ] **Step 4: Commit**

```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
mkdir -p docs/superpowers/plans/grumman-llv-admin-handoff
git add docs/superpowers/plans/grumman-llv-admin-handoff/cms-page-content.html
git commit -m "$(cat <<'EOF'
Add CMS page content HTML deliverable for Grumman LLV landing page

Ready to paste into the Magento CMS page's Content field. No header,
topmenu, or footer markup included — those render from the real theme.

Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
EOF
)"
```

---

### Task 4: Write the Layout Update XML snippet

**Files:**
- Create: `docs/superpowers/plans/grumman-llv-admin-handoff/layout-update.xml`

**Interfaces:**
- Consumes: filename from Task 2 (`landing-grumman-llv.css`)
- Produces: the exact XML the user will paste into the CMS page's Design → Layout Update XML field (Task 6 references this file's path)

- [ ] **Step 1: Write the XML snippet**

Create `docs/superpowers/plans/grumman-llv-admin-handoff/layout-update.xml`:

```xml
<reference name="root">
    <action method="addBodyClass">
        <class>usps-grumman-llv-pcm-repair</class>
    </action>
</reference>
<reference name="head">
    <action method="addItem">
        <type>skin_css</type>
        <name>css/landing-grumman-llv.css</name>
    </action>
</reference>
```

- [ ] **Step 2: Verify the XML is well-formed**

Run:
```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
python3 -c "
import xml.dom.minidom as m
content = open('docs/superpowers/plans/grumman-llv-admin-handoff/layout-update.xml', encoding='utf-8').read()
m.parseString(f'<root>{content}</root>')
print('well-formed')
"
```
Expected: `well-formed`. (Magento wraps this snippet in an outer `<layout>` element itself when the page is saved — this file intentionally contains only the fragment that goes inside it, matching what the CMS page's Layout Update XML field expects. The file has more than one top-level `<reference>` sibling, which is a valid XML fragment but not a standalone well-formed document on its own — parsing it wrapped in a synthetic `<root>` element, as this command does, is the correct way to validate a multi-root fragment like this one. Parsing the raw file directly with `m.parse(...)` will fail with "junk after document element" once there's more than one top-level element — that failure is expected and not a sign the file is broken.)

- [ ] **Step 3: Commit**

```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
git add docs/superpowers/plans/grumman-llv-admin-handoff/layout-update.xml
git commit -m "$(cat <<'EOF'
Add Layout Update XML deliverable for Grumman LLV landing page

Scopes landing-grumman-llv.css to load on this one CMS page only.

Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
EOF
)"
```

---

### Task 5: Local visual verification

**Files:**
- Create (temporary, not committed): `/tmp/claude-*/scratchpad/llv-preview/preview.html` (or equivalent scratchpad path) — a standalone harness, not part of the deliverable
- No permanent files created by this task

**Interfaces:**
- Consumes: `skin/frontend/rwd/default/css/landing-grumman-llv.css` (Task 2), the four images (Task 1), `docs/superpowers/plans/grumman-llv-admin-handoff/cms-page-content.html` (Task 3)
- Produces: visual confirmation only — no code artifact

- [ ] **Step 1: Build a standalone preview page**

Create a scratch file (e.g. `/tmp/claude-1000/-home-mr-esh/<session>/scratchpad/llv-preview/preview.html`) that:
- Links `landing-grumman-llv.css` via a relative or `file://`-safe path (serve over local HTTP, same technique as the mockup extraction — `python3 -m http.server` from a directory containing both the CSS, the `images/` folder, and this preview file)
- Pastes in the literal content of `cms-page-content.html` from Task 3, with the `{{skin url='...'}}` directives manually replaced by plain relative paths (e.g. `images/hero-engine-bay.jpg`) since this is a static preview, not real Magento
- Wraps that content in two visibly-different colored bands above and below (e.g. `<div style="background:red;height:200px">FAKE HEADER ZONE</div>` ... content ... `<div style="background:red;height:200px">FAKE FOOTER ZONE</div>`) — the point of the red bands is to make it obvious at a glance if any of our CSS rules bled outside `.landing-grumman-llv` and started styling things outside it

- [ ] **Step 2: Serve and screenshot the preview at desktop and mobile widths**

Serve the directory locally (`python3 -m http.server <port> --bind 127.0.0.1`), open in the browser tool, and take screenshots at a desktop width (e.g. 1440px) and a mobile width (e.g. 390px, using the browser tool's resize capability or a mobile viewport). Scroll through the full page at each width.

Expected: every section renders per the design (hero, 4 trust badges overlapping the hero, common-problems 2-column grid collapsing to 1 column on mobile, specialist section, 3-point checklist overlapping the photo strip, 4-step process, why-repair-original grid collapsing to a single column on mobile, quote/contact panel). The red "FAKE HEADER ZONE" / "FAKE FOOTER ZONE" bands remain plain red with no font, color, spacing, or layout changes at either width — confirming the stylesheet cannot affect real header/footer content once deployed.

- [ ] **Step 3: Tear down**

Stop the local HTTP server and close the browser tab. Do not commit anything from this task — it's a verification step only, not a deliverable.

---

### Task 6: Write admin handoff instructions

**Files:**
- Create: `docs/superpowers/plans/grumman-llv-admin-handoff/ADMIN-STEPS.md`

**Interfaces:**
- Consumes: file paths from Tasks 1–4; field values from the design spec's Global Constraints
- Produces: the document the user follows to create the CMS page by hand

- [ ] **Step 1: Write the instructions**

Create `docs/superpowers/plans/grumman-llv-admin-handoff/ADMIN-STEPS.md`:

```markdown
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
8. **Flush the cache:** go to **System > Cache Management** and click
   "Flush Magento Cache" before checking the live page. Otherwise the new
   CSS may not appear yet and it can look like something's broken when it
   isn't.
9. **Verify:**
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
that file is scoped under `.landing-grumman-llv` EXCEPT a handful of
clearly-commented "SANCTIONED EXCEPTIONS" grouped near the top of the file
(suppressing the horizontal-scrollbar risk, hiding the theme's page-title
banner, adjusting `.main-content` spacing, and hiding the shared footer's
copyright line — all specific to this page). Every one of those exceptions
is scoped to `body.usps-grumman-llv-pcm-repair`, a class that only exists
on this page — so it's safe to edit the file without risk of touching any
other page, as long as you don't remove any exception's scoping class.
```

- [ ] **Step 2: Verify referenced files exist**

Run:
```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html/docs/superpowers/plans/grumman-llv-admin-handoff
ls cms-page-content.html layout-update.xml ADMIN-STEPS.md
```
Expected: all three files listed, no "No such file" errors.

- [ ] **Step 3: Commit**

```bash
cd /mnt/c/Users/mresm/OneDrive/Desktop/pacific_projects/solopcms/public_html
git add docs/superpowers/plans/grumman-llv-admin-handoff/ADMIN-STEPS.md
git commit -m "$(cat <<'EOF'
Add admin handoff instructions for Grumman LLV landing page

Step-by-step CMS Page setup so the page owner can create it without
needing a developer once this branch is deployed.

Co-Authored-By: Claude Sonnet 5 <noreply@anthropic.com>
EOF
)"
```
