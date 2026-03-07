"""
UI Tests for llmdev.resayil.io using Playwright sync API
"""
import os
import sys
import time
from datetime import datetime
from playwright.sync_api import sync_playwright, TimeoutError as PlaywrightTimeoutError

BASE_URL = "https://llmdev.resayil.io"
EMAIL = "perftest@llm.resayil.io"
PASSWORD = "PerfTest2026!"
SCREENSHOTS_DIR = r"C:\Users\User\OneDrive - City Travelers\LLM-Resayil\.planning\phases\12-saied-api-fix\ui-screenshots"
RESULTS_FILE = r"C:\Users\User\OneDrive - City Travelers\LLM-Resayil\.planning\phases\12-saied-api-fix\ui-test-results.md"

os.makedirs(SCREENSHOTS_DIR, exist_ok=True)

results = []

def screenshot_path(name):
    return os.path.join(SCREENSHOTS_DIR, name)

def log(msg):
    print(msg, flush=True)

def record(test_name, status, notes="", screenshot=""):
    entry = {
        "name": test_name,
        "status": status,
        "notes": notes,
        "screenshot": screenshot,
    }
    results.append(entry)
    icon = "PASS" if status == "PASS" else "FAIL"
    log(f"  [{icon}] {test_name}: {notes}")

def run_tests():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 1280, "height": 900},
            locale="en-US",
        )
        page = context.new_page()

        # ------------------------------------------------------------------
        # 1. LANDING PAGE
        # ------------------------------------------------------------------
        log("\n=== 1. LANDING PAGE ===")
        try:
            page.goto(BASE_URL, wait_until="networkidle", timeout=30000)
            shot = screenshot_path("landing.png")
            page.screenshot(path=shot, full_page=True)

            title = page.title()
            log(f"  Title: {title}")
            title_ok = len(title) > 0
            record("Landing - title", "PASS" if title_ok else "FAIL",
                   f"Title: '{title}'", "landing.png")

            # Hero section: look for common hero identifiers
            hero_selectors = [
                "section.hero", ".hero", "[class*='hero']",
                "h1", "[class*='banner']", "[class*='jumbotron']",
                "main h1", "header h1", ".landing",
            ]
            hero_visible = False
            hero_found = ""
            for sel in hero_selectors:
                try:
                    el = page.locator(sel).first
                    if el.is_visible(timeout=2000):
                        hero_visible = True
                        hero_found = sel
                        break
                except Exception:
                    pass

            record("Landing - hero visible", "PASS" if hero_visible else "FAIL",
                   f"Found via: {hero_found or 'none'}", "landing.png")

        except Exception as e:
            record("Landing page", "FAIL", str(e))

        # ------------------------------------------------------------------
        # 2. FAQ PAGE (EN + AR)
        # ------------------------------------------------------------------
        log("\n=== 2. FAQ PAGE ===")
        try:
            page.goto(f"{BASE_URL}/faq", wait_until="networkidle", timeout=30000)
            shot_en = screenshot_path("faq-en.png")
            page.screenshot(path=shot_en, full_page=True)

            # Check FAQ items: look for question/answer structure
            faq_selectors = [
                ".faq", "[class*='faq']", "details", "summary",
                "[class*='accordion']", "[class*='question']",
                "dl", "dt", ".q-and-a",
            ]
            faq_visible = False
            faq_found = ""
            for sel in faq_selectors:
                try:
                    els = page.locator(sel)
                    if els.count() > 0 and els.first.is_visible(timeout=2000):
                        faq_visible = True
                        faq_found = f"{sel} (count={els.count()})"
                        break
                except Exception:
                    pass

            # Fallback: check page has substantial content
            body_text = page.locator("body").inner_text()
            has_content = len(body_text.strip()) > 200

            record("FAQ EN - items visible", "PASS" if (faq_visible or has_content) else "FAIL",
                   faq_found or f"body text length={len(body_text)}", "faq-en.png")

        except Exception as e:
            record("FAQ EN", "FAIL", str(e))

        # Switch to Arabic
        try:
            page.goto(f"{BASE_URL}/locale/ar", wait_until="networkidle", timeout=15000)
            page.goto(f"{BASE_URL}/faq", wait_until="networkidle", timeout=30000)
            shot_ar = screenshot_path("faq-ar.png")
            page.screenshot(path=shot_ar, full_page=True)

            # Check for Arabic characters or RTL
            html = page.content()
            ar_content = any(ord(c) >= 0x0600 and ord(c) <= 0x06FF for c in html)
            rtl = 'dir="rtl"' in html or "direction: rtl" in html or 'lang="ar"' in html

            record("FAQ AR - Arabic/RTL content", "PASS" if (ar_content or rtl) else "FAIL",
                   f"Arabic chars: {ar_content}, RTL: {rtl}", "faq-ar.png")

            # Switch back to English for subsequent tests
            page.goto(f"{BASE_URL}/locale/en", wait_until="networkidle", timeout=15000)

        except Exception as e:
            record("FAQ AR", "FAIL", str(e))

        # ------------------------------------------------------------------
        # 3. FEATURES PAGE (EN + AR)
        # ------------------------------------------------------------------
        log("\n=== 3. FEATURES PAGE ===")
        try:
            page.goto(f"{BASE_URL}/features", wait_until="networkidle", timeout=30000)
            shot_en = screenshot_path("features-en.png")
            page.screenshot(path=shot_en, full_page=True)

            body_text = page.locator("body").inner_text()
            has_content = len(body_text.strip()) > 100
            record("Features EN - page loads", "PASS" if has_content else "FAIL",
                   f"body text length={len(body_text)}", "features-en.png")

        except Exception as e:
            record("Features EN", "FAIL", str(e))

        try:
            page.goto(f"{BASE_URL}/locale/ar", wait_until="networkidle", timeout=15000)
            page.goto(f"{BASE_URL}/features", wait_until="networkidle", timeout=30000)
            shot_ar = screenshot_path("features-ar.png")
            page.screenshot(path=shot_ar, full_page=True)

            html = page.content()
            ar_content = any(ord(c) >= 0x0600 and ord(c) <= 0x06FF for c in html)
            rtl = 'dir="rtl"' in html or "direction: rtl" in html or 'lang="ar"' in html

            record("Features AR - Arabic/RTL content", "PASS" if (ar_content or rtl) else "FAIL",
                   f"Arabic chars: {ar_content}, RTL: {rtl}", "features-ar.png")

            # Switch back to English
            page.goto(f"{BASE_URL}/locale/en", wait_until="networkidle", timeout=15000)

        except Exception as e:
            record("Features AR", "FAIL", str(e))

        # ------------------------------------------------------------------
        # 4. DOCS PAGE
        # ------------------------------------------------------------------
        log("\n=== 4. DOCS PAGE ===")
        try:
            page.goto(f"{BASE_URL}/docs", wait_until="networkidle", timeout=30000)
            shot = screenshot_path("docs.png")
            page.screenshot(path=shot, full_page=True)

            body_text = page.locator("body").inner_text()
            has_content = len(body_text.strip()) > 100
            record("Docs - page loads", "PASS" if has_content else "FAIL",
                   f"body text length={len(body_text)}", "docs.png")

            # Look for "Getting Started" link
            gs_selectors = [
                'a:has-text("Getting Started")',
                'a:has-text("getting started")',
                '[href*="getting-started"]',
                '[href*="getting_started"]',
                'a:has-text("Get Started")',
            ]
            gs_found = False
            gs_href = ""
            for sel in gs_selectors:
                try:
                    el = page.locator(sel).first
                    if el.count() > 0 or el.is_visible(timeout=2000):
                        gs_href = el.get_attribute("href") or ""
                        gs_found = True
                        break
                except Exception:
                    pass

            if gs_found:
                try:
                    page.locator(gs_selectors[0] if 'Getting Started' in gs_selectors[0] else gs_selectors[0]).first.click(timeout=5000)
                    page.wait_for_load_state("networkidle", timeout=15000)
                    nav_url = page.url
                    record("Docs - Getting Started navigation", "PASS",
                           f"Navigated to: {nav_url}", "docs.png")
                except Exception as e:
                    record("Docs - Getting Started click", "FAIL", str(e), "docs.png")
            else:
                record("Docs - Getting Started link", "FAIL",
                       "Link not found on page", "docs.png")

        except Exception as e:
            record("Docs page", "FAIL", str(e))

        # ------------------------------------------------------------------
        # 5. LOGIN + DASHBOARD
        # ------------------------------------------------------------------
        log("\n=== 5. LOGIN + DASHBOARD ===")
        try:
            page.goto(f"{BASE_URL}/login", wait_until="networkidle", timeout=30000)

            # Fill email
            email_selectors = ['input[type="email"]', 'input[name="email"]', '#email']
            email_filled = False
            for sel in email_selectors:
                try:
                    el = page.locator(sel).first
                    if el.is_visible(timeout=3000):
                        el.fill(EMAIL)
                        email_filled = True
                        break
                except Exception:
                    pass

            # Fill password
            pw_selectors = ['input[type="password"]', 'input[name="password"]', '#password']
            pw_filled = False
            for sel in pw_selectors:
                try:
                    el = page.locator(sel).first
                    if el.is_visible(timeout=3000):
                        el.fill(PASSWORD)
                        pw_filled = True
                        break
                except Exception:
                    pass

            if not email_filled or not pw_filled:
                record("Login - form fill", "FAIL",
                       f"email_filled={email_filled}, pw_filled={pw_filled}")
            else:
                record("Login - form fill", "PASS", "Email and password filled")

            # Submit
            submit_selectors = [
                'button[type="submit"]',
                'input[type="submit"]',
                'button:has-text("Login")',
                'button:has-text("Sign in")',
                'button:has-text("Log in")',
                'button:has-text("Sign In")',
            ]
            submitted = False
            for sel in submit_selectors:
                try:
                    el = page.locator(sel).first
                    if el.is_visible(timeout=3000):
                        el.click()
                        submitted = True
                        break
                except Exception:
                    pass

            if not submitted:
                record("Login - submit", "FAIL", "Submit button not found")
            else:
                # Wait for redirect
                try:
                    page.wait_for_url(lambda url: "/login" not in url, timeout=20000)
                    record("Login - redirect", "PASS", f"Landed at: {page.url}")
                except Exception:
                    # Maybe already on dashboard or error
                    current = page.url
                    if "/login" not in current:
                        record("Login - redirect", "PASS", f"URL: {current}")
                    else:
                        page_text = page.locator("body").inner_text()
                        record("Login - redirect", "FAIL",
                               f"Still on login. Body snippet: {page_text[:300]}")

                page.wait_for_load_state("networkidle", timeout=15000)
                shot = screenshot_path("dashboard.png")
                page.screenshot(path=shot, full_page=True)
                log(f"  Dashboard URL: {page.url}")

                # Verify dashboard elements
                dashboard_selectors = [
                    "[class*='credit']", "[class*='Credit']",
                    "[class*='balance']", "[class*='dashboard']",
                    "[class*='api']", "[class*='API']",
                    ".stat", ".card", "[class*='usage']",
                    "h1", "h2",
                ]
                dashboard_visible = False
                dashboard_found = ""
                for sel in dashboard_selectors:
                    try:
                        el = page.locator(sel).first
                        if el.is_visible(timeout=2000):
                            dashboard_visible = True
                            dashboard_found = sel
                            break
                    except Exception:
                        pass

                body_text = page.locator("body").inner_text()
                has_credit = "credit" in body_text.lower() or "api" in body_text.lower()

                record("Dashboard - content visible", "PASS" if (dashboard_visible or has_credit) else "FAIL",
                       f"Selector: {dashboard_found}, has credit/API text: {has_credit}", "dashboard.png")

        except Exception as e:
            record("Login/Dashboard", "FAIL", str(e))

        # ------------------------------------------------------------------
        # 6. API KEYS PAGE
        # ------------------------------------------------------------------
        log("\n=== 6. API KEYS PAGE ===")
        try:
            # Try direct navigation first
            api_key_urls = [
                f"{BASE_URL}/api-keys",
                f"{BASE_URL}/dashboard/api-keys",
                f"{BASE_URL}/settings/api-keys",
                f"{BASE_URL}/keys",
            ]

            api_page_found = False
            api_url_used = ""
            for url in api_key_urls:
                try:
                    resp = page.goto(url, wait_until="networkidle", timeout=15000)
                    if resp and resp.status < 400 and "/login" not in page.url:
                        api_page_found = True
                        api_url_used = url
                        break
                    elif "/login" in page.url:
                        # Got redirected to login, stop trying
                        break
                except Exception:
                    pass

            if not api_page_found:
                # Try finding API keys link on current page (dashboard)
                page.goto(f"{BASE_URL}/", wait_until="networkidle", timeout=15000)
                api_link_selectors = [
                    'a:has-text("API Keys")',
                    'a:has-text("API keys")',
                    'a:has-text("Api Keys")',
                    '[href*="api-key"]',
                    '[href*="apikey"]',
                ]
                for sel in api_link_selectors:
                    try:
                        el = page.locator(sel).first
                        if el.is_visible(timeout=3000):
                            el.click()
                            page.wait_for_load_state("networkidle", timeout=15000)
                            api_page_found = True
                            api_url_used = page.url
                            break
                    except Exception:
                        pass

            shot = screenshot_path("api-keys.png")
            page.screenshot(path=shot, full_page=True)
            log(f"  API keys URL: {page.url}")

            body_text = page.locator("body").inner_text()
            has_key_content = (
                "api" in body_text.lower()
                or "key" in body_text.lower()
                or "token" in body_text.lower()
            )

            record("API Keys - page accessible", "PASS" if api_page_found else "FAIL",
                   f"URL: {api_url_used or page.url}", "api-keys.png")
            record("API Keys - content visible", "PASS" if has_key_content else "FAIL",
                   f"has key-related content: {has_key_content}", "api-keys.png")

        except Exception as e:
            record("API Keys page", "FAIL", str(e))

        browser.close()

    # ------------------------------------------------------------------
    # Write results
    # ------------------------------------------------------------------
    now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    passes = sum(1 for r in results if r["status"] == "PASS")
    fails = sum(1 for r in results if r["status"] == "FAIL")

    md = f"""# UI Test Results

**Date:** {now}
**URL:** {BASE_URL}
**Total:** {len(results)} checks | **PASS:** {passes} | **FAIL:** {fails}

---

## Results

| # | Test | Status | Notes | Screenshot |
|---|------|--------|-------|------------|
"""
    for i, r in enumerate(results, 1):
        status_cell = "PASS" if r["status"] == "PASS" else "FAIL"
        shot_cell = f"`{r['screenshot']}`" if r["screenshot"] else "-"
        notes = r["notes"].replace("|", "\\|").replace("\n", " ")[:120]
        md += f"| {i} | {r['name']} | {status_cell} | {notes} | {shot_cell} |\n"

    md += f"""
---

## Screenshots

"""
    seen = set()
    for r in results:
        if r["screenshot"] and r["screenshot"] not in seen:
            seen.add(r["screenshot"])
            full_path = os.path.join(SCREENSHOTS_DIR, r["screenshot"])
            md += f"- **{r['screenshot']}** — `{full_path}`\n"

    md += f"""
---

## Summary

- Passed: {passes}/{len(results)}
- Failed: {fails}/{len(results)}
"""

    if fails > 0:
        md += "\n### Failed Checks\n\n"
        for r in results:
            if r["status"] == "FAIL":
                md += f"- **{r['name']}**: {r['notes']}\n"

    with open(RESULTS_FILE, "w", encoding="utf-8") as f:
        f.write(md)

    log(f"\n{'='*60}")
    log(f"Results: {passes} PASS, {fails} FAIL out of {len(results)} checks")
    log(f"Results written to: {RESULTS_FILE}")
    log(f"Screenshots in: {SCREENSHOTS_DIR}")

if __name__ == "__main__":
    run_tests()
