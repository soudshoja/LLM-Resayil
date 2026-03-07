@extends('layouts.app')

@section('title', $pageTitle ?? 'Pricing — LLM Resayil')

@push('styles')
<style>
/* ══════════════════════════════════════════════════
   PRICING PAGE — COMPLETE REDESIGN
   Dark Luxury · Gold Accents · Premium SaaS
══════════════════════════════════════════════════ */

/* ── Layout shell ── */
.pricing-page {
    background: var(--bg-secondary);
    min-height: 100vh;
}

/* ── Shared typography helpers ── */
.pr-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.74rem; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; color: var(--gold); margin-bottom: 1rem;
}
.pr-label::before {
    content: ''; display: block; width: 18px; height: 2px;
    background: var(--gold); border-radius: 2px;
}
.pr-h2 {
    font-size: clamp(1.8rem, 4vw, 2.75rem);
    font-weight: 800; line-height: 1.15;
    letter-spacing: -0.025em; margin-bottom: 0.85rem;
}
.pr-sub {
    font-size: 1rem; color: var(--text-secondary);
    line-height: 1.7; max-width: 580px;
}
.pr-section {
    max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 5rem;
}
.pr-center { text-align: center; }
.pr-center .pr-sub { margin-left: auto; margin-right: auto; }

/* ── Scroll-reveal ── */
.fu { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
.fu.on { opacity: 1; transform: none; }
.d1 { transition-delay: 0.10s; }
.d2 { transition-delay: 0.20s; }
.d3 { transition-delay: 0.30s; }
@media (prefers-reduced-motion: reduce) { .fu { opacity:1; transform:none; transition:none; } }

/* ═══════════════════════════════════════
   HERO
═══════════════════════════════════════ */
.pr-hero {
    position: relative; overflow: hidden;
    padding: 5.5rem 1.5rem 4.5rem;
    text-align: center;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(212,175,55,0.08) 0%, transparent 70%);
}
.pr-hero::after {
    content: ''; position: absolute; inset: 0; pointer-events: none;
    background-image: linear-gradient(rgba(255,255,255,0.016) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,0.016) 1px, transparent 1px);
    background-size: 72px 72px;
}
.pr-hero-inner { max-width: 760px; margin: 0 auto; position: relative; z-index: 1; }
.pr-eyebrow {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(212,175,55,0.09); border: 1px solid rgba(212,175,55,0.22);
    border-radius: 100px; padding: 0.45rem 1.1rem;
    font-size: 0.82rem; font-weight: 600; color: var(--gold);
    margin-bottom: 1.75rem;
}
.pr-eyebrow svg { width: 14px; height: 14px; }
.pr-hero-h1 {
    font-size: clamp(2.4rem, 6vw, 4rem);
    font-weight: 900; line-height: 1.08; letter-spacing: -0.03em;
    margin-bottom: 1.25rem;
}
.pr-hero-h1 span {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.pr-hero-lead {
    font-size: 1.1rem; color: var(--text-secondary); line-height: 1.72; margin-bottom: 2.25rem;
}
.pr-free-notice {
    display: inline-flex; align-items: center; gap: 0.6rem;
    background: rgba(5,150,105,0.10); border: 1px solid rgba(5,150,105,0.25);
    color: #6ee7b7; border-radius: 10px;
    padding: 0.65rem 1.25rem; font-size: 0.88rem; font-weight: 600;
}
.pr-free-notice svg { width: 15px; height: 15px; flex-shrink: 0; }

/* ═══════════════════════════════════════
   CREDIT PACKS — COMPLETE REDESIGN
═══════════════════════════════════════ */

/* Grid */
.packs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    align-items: stretch;
    margin-bottom: 2rem;
}
@media (max-width: 860px) {
    .packs-grid {
        grid-template-columns: 1fr;
        max-width: 420px;
        margin-left: auto;
        margin-right: auto;
    }
}

/* Base card */
.pack-card {
    background: #13161d;
    border: 1px solid #1e2230;
    border-radius: 20px;
    padding: 0 0 2rem;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    cursor: pointer;
    transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
}
.pack-card:hover {
    border-color: rgba(212,175,55,0.4);
    box-shadow: 0 0 40px rgba(212,175,55,0.10), 0 20px 56px rgba(0,0,0,0.35);
    transform: translateY(-5px);
}

/* Top accent line — all cards get a subtle version */
.pack-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(212,175,55,0.25), transparent);
    pointer-events: none;
}

/* ── Featured (Growth — Best Value) ── */
.pack-card.featured {
    border-color: rgba(212,175,55,0.5);
    background: linear-gradient(170deg, #15192200 0%, rgba(212,175,55,0.055) 100%), #13161d;
    box-shadow: 0 0 60px rgba(212,175,55,0.13), 0 20px 56px rgba(0,0,0,0.32);
}
.pack-card.featured::before {
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.pack-card.featured:hover {
    transform: translateY(-7px);
    box-shadow: 0 0 80px rgba(212,175,55,0.20), 0 24px 64px rgba(0,0,0,0.40);
}

/* ── Popular (Pro — Most Popular) ── */
.pack-card.popular {
    border-color: rgba(212,175,55,0.38);
    background: linear-gradient(170deg, rgba(212,175,55,0.04) 0%, #13161d 60%), #13161d;
}
.pack-card.popular:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 48px rgba(212,175,55,0.13), 0 20px 56px rgba(0,0,0,0.35);
}

/* ── Card header strip (holds badge) ── */
.pack-header {
    padding: 1.5rem 2rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

/* ── Inline badge — INSIDE card, never overflows ── */
.pack-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.28rem 0.75rem;
    border-radius: 100px;
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    white-space: nowrap;
    width: fit-content;
}
.pack-badge.badge-value {
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0f1115;
}
.pack-badge.badge-popular {
    background: rgba(212,175,55,0.14);
    color: var(--gold);
    border: 1px solid rgba(212,175,55,0.35);
}
.pack-badge svg { width: 10px; height: 10px; flex-shrink: 0; }

/* ── Pack name row ── */
.pack-name {
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.01em;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.tier-svg-icon { flex-shrink: 0; vertical-align: middle; }

/* ── Card body ── */
.pack-body {
    padding: 1.5rem 2rem 0;
    display: flex;
    flex-direction: column;
    flex: 1;
}

/* Credits — big gold number */
.pack-credits-row {
    margin-bottom: 0.2rem;
}
.pack-credits {
    font-size: 3.25rem;
    font-weight: 900;
    color: var(--gold);
    line-height: 1;
    letter-spacing: -0.02em;
}
.pack-credits-unit {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gold);
    opacity: 0.75;
    margin-left: 0.15rem;
}
.pack-credits-label {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-bottom: 1.25rem;
    margin-top: 0.15rem;
}

/* Bonus badge pill — inline under credits */
.pack-bonus-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    background: rgba(16,185,129,0.10);
    border: 1px solid rgba(16,185,129,0.25);
    color: #6ee7b7;
    border-radius: 100px;
    padding: 0.22rem 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    margin-bottom: 1.25rem;
    width: fit-content;
}
.pack-bonus-pill svg { width: 10px; height: 10px; flex-shrink: 0; }

/* Divider */
.pack-divider {
    height: 1px;
    background: rgba(255,255,255,0.05);
    margin: 0 0 1.25rem;
}

/* Price block */
.pack-price-block {
    margin-bottom: 1.5rem;
}
.pack-price {
    font-size: 2.1rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.3rem;
}
.pack-price .currency {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-left: 0.2rem;
}
.pack-per-credit {
    font-size: 0.78rem;
    color: var(--text-muted);
}

/* CTA button */
.pack-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    width: 100%;
    padding: 0.9rem 1.5rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0f1115;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    transition: opacity 0.2s ease, transform 0.2s ease;
    border: none;
    cursor: pointer;
    margin-bottom: 0;
    margin-top: auto;
}
.pack-cta svg { width: 14px; height: 14px; flex-shrink: 0; }
.pack-cta:hover { opacity: 0.88; transform: translateY(-1px); color: #0f1115; }

.pack-cta-ghost {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    width: 100%;
    padding: 0.9rem 1.5rem;
    background: transparent;
    color: var(--gold);
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.9rem;
    text-decoration: none;
    transition: background 0.2s ease, border-color 0.2s ease;
    cursor: pointer;
    margin-bottom: 0;
    margin-top: auto;
}
.pack-cta-ghost svg { width: 14px; height: 14px; flex-shrink: 0; }
.pack-cta-ghost:hover { background: rgba(212,175,55,0.08); border-color: var(--gold); color: var(--gold); }

/* Feature list */
.pack-features {
    list-style: none;
    margin-top: 1.5rem;
    padding: 0;
}
.pack-features li {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.85rem;
    color: var(--text-secondary);
    padding: 0.28rem 0;
    line-height: 1.5;
}
.pack-features .chk {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}
.pack-features .chk svg { width: 13px; height: 13px; color: var(--gold); }

/* ═══════════════════════════════════════
   FREE TIER CARD
═══════════════════════════════════════ */
.free-tier-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px; padding: 2.5rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 2rem; margin-top: 3rem; flex-wrap: wrap;
    position: relative; overflow: hidden;
}
.free-tier-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(5,150,105,0.4), transparent);
}
.free-tier-icon {
    width: 52px; height: 52px; border-radius: 12px; flex-shrink: 0;
    background: rgba(5,150,105,0.12); border: 1px solid rgba(5,150,105,0.25);
    display: flex; align-items: center; justify-content: center;
}
.free-tier-icon .tier-svg-icon { width: 32px; height: 32px; }
.free-tier-body { flex: 1; }
.free-tier-body h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem; }
.free-tier-body p { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.65; max-width: 520px; }
.btn-free {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0f1115; padding: 0.85rem 1.75rem;
    border-radius: 10px; font-weight: 700; font-size: 0.9rem;
    text-decoration: none; white-space: nowrap;
    transition: opacity 0.2s, transform 0.2s;
}
.btn-free:hover { opacity: 0.88; transform: translateY(-1px); color: #0f1115; }

/* ═══════════════════════════════════════
   HOW CREDITS WORK
═══════════════════════════════════════ */
.how-credits-flow {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 1px; background: var(--border);
    border: 1px solid var(--border); border-radius: 16px; overflow: hidden;
    margin-top: 2.5rem;
}
@media (max-width: 860px) { .how-credits-flow { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 520px)  { .how-credits-flow { grid-template-columns: 1fr; } }

.hcf-step {
    background: var(--bg-card); padding: 2rem 1.75rem;
    position: relative;
}
.hcf-num {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0a0d14; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 0.95rem;
    margin-bottom: 1rem;
    box-shadow: 0 0 18px rgba(212,175,55,0.3);
}
.hcf-step h4 { font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem; }
.hcf-step p  { font-size: 0.84rem; color: var(--text-secondary); line-height: 1.65; }

/* ═══════════════════════════════════════
   MODEL TIERS TABLE
═══════════════════════════════════════ */
.tiers-table-wrap {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden; margin-top: 2.5rem;
}
.tiers-table {
    width: 100%; border-collapse: collapse;
}
.tiers-table thead tr {
    background: rgba(0,0,0,0.25); border-bottom: 1px solid var(--border);
}
.tiers-table th {
    padding: 1rem 1.25rem; text-align: left;
    font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-muted);
}
.tiers-table td {
    padding: 1.1rem 1.25rem; font-size: 0.9rem; color: var(--text-secondary);
    border-bottom: 1px solid var(--border);
}
.tiers-table tbody tr:last-child td { border-bottom: none; }
.tiers-table tbody tr:hover td { background: rgba(255,255,255,0.01); }
.tier-badge {
    display: inline-block; padding: 0.28rem 0.8rem;
    border-radius: 6px; font-size: 0.75rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
}
.tier-badge.standard { background: rgba(99,102,241,0.14); color: #818cf8; }
.tier-badge.premium  { background: rgba(212,175,55,0.12); color: var(--gold); }

/* ═══════════════════════════════════════
   CREDIT MULTIPLIER SYSTEM
═══════════════════════════════════════ */
.multiplier-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-top: 2.5rem;
}
@media (max-width: 640px) {
    .multiplier-grid { grid-template-columns: 1fr; }
}
.multiplier-card {
    background: #13161d;
    border: 1px solid #1e2230;
    border-radius: 16px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}
.multiplier-card.frontier {
    border-color: rgba(212,175,55,0.3);
}
.multiplier-card.frontier::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, rgba(212,175,55,0.6), rgba(212,175,55,0.2));
    border-radius: 16px 16px 0 0;
}
.multiplier-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}
.multiplier-card-label {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
}
.multiplier-card-desc {
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
    line-height: 1.5;
}
.multiplier-size-rows {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.multiplier-size-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.multiplier-size-row:last-child {
    border-bottom: none;
}
.multiplier-size-left {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}
.multiplier-size-icon { display:flex; align-items:center; flex-shrink:0; }
.mult-badge {
    display: inline-block;
    padding: 0.18rem 0.6rem;
    border-radius: 5px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.mult-badge.standard { background: rgba(96,165,250,0.12); color: #60a5fa; }
.mult-badge.frontier { background: rgba(212,175,55,0.12); color: #d4af37; }
.multiplier-size-name {
    font-size: 0.88rem;
    color: var(--text-secondary);
}
.multiplier-size-params {
    font-size: 0.75rem;
    color: var(--text-muted);
}
.multiplier-cost {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap;
}
.multiplier-cost span {
    font-size: 0.78rem;
    font-weight: 400;
    color: var(--text-muted);
}
.multiplier-note {
    text-align: center;
    font-size: 0.82rem;
    color: var(--text-muted);
    margin-top: 1.5rem;
}

/* ═══════════════════════════════════════
   FAQ ACCORDION
═══════════════════════════════════════ */
.faq-list { max-width: 740px; margin: 2.5rem auto 0; }
.faq-item {
    border-bottom: 1px solid var(--border);
}
.faq-item:last-child { border-bottom: none; }
.faq-trigger {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 1.25rem 0; cursor: pointer;
    background: none; border: none; text-align: left;
    gap: 1rem;
}
[dir="rtl"] .faq-trigger { text-align: right; }
.faq-q {
    font-size: 0.95rem; font-weight: 600;
    color: var(--text-primary); line-height: 1.5;
    transition: color 0.2s;
}
.faq-trigger:hover .faq-q { color: var(--gold); }
.faq-icon {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.2);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: background 0.2s, transform 0.3s;
}
.faq-icon svg { width: 12px; height: 12px; color: var(--gold); transition: transform 0.3s; }
.faq-item.open .faq-icon { background: rgba(212,175,55,0.16); }
.faq-item.open .faq-icon svg { transform: rotate(45deg); }
.faq-body { display: none; padding: 0 0 1.25rem; }
.faq-body.open { display: block; }
.faq-body p {
    font-size: 0.9rem; color: var(--text-secondary); line-height: 1.75;
}
.faq-body a { color: var(--gold); }
.faq-body a:hover { text-decoration: underline; }

/* ═══════════════════════════════════════
   FINAL CTA BAND
═══════════════════════════════════════ */
.pr-cta-band {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
    border-radius: 24px; padding: 4.5rem 2.5rem;
    text-align: center; position: relative; overflow: hidden;
    margin-bottom: 2rem;
}
.pr-cta-band::before {
    content: ''; position: absolute; inset: 0; border-radius: 24px;
    background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.12) 100%);
    pointer-events: none;
}
.pr-cta-band h2 {
    font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 900;
    color: #0a0d14; letter-spacing: -0.02em; line-height: 1.15;
    margin-bottom: 1rem; position: relative;
}
.pr-cta-band p {
    font-size: 1rem; color: rgba(10,13,20,0.7);
    margin-bottom: 2.25rem; line-height: 1.7; position: relative;
}
.pr-cta-btns { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; position: relative; }
.btn-cta-dark {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: #0a0d14; color: var(--gold);
    padding: 0.95rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    transition: all 0.2s; white-space: nowrap; text-decoration: none;
}
.btn-cta-dark:hover { background: #13161d; color: var(--gold); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.35); }
.btn-cta-outline {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: transparent; color: #0a0d14;
    border: 2px solid rgba(10,13,20,0.3);
    padding: 0.95rem 2.25rem; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    transition: all 0.2s; white-space: nowrap; text-decoration: none;
}
.btn-cta-outline:hover { background: rgba(10,13,20,0.08); border-color: rgba(10,13,20,0.5); color: #0a0d14; }

/* ═══════════════════════════════════════
   TRIAL BANNER
═══════════════════════════════════════ */
.trial-banner {
    max-width: 1100px; margin: 0 auto; padding: 3.5rem 1.5rem 0;
}
.trial-banner-inner {
    background: linear-gradient(135deg, rgba(212,175,55,0.07) 0%, rgba(212,175,55,0.03) 100%);
    border: 1px solid rgba(212,175,55,0.35);
    border-radius: 24px; padding: 2.75rem 3rem;
    position: relative; overflow: hidden;
    display: flex; align-items: center; gap: 3rem;
}
.trial-banner-inner::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
}
.trial-banner-inner::after {
    content: ''; position: absolute; bottom: -60px; right: -60px;
    width: 200px; height: 200px; border-radius: 50%;
    background: radial-gradient(circle, rgba(212,175,55,0.08) 0%, transparent 70%);
    pointer-events: none;
}
.trial-badge-pill {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0f1115; padding: 0.3rem 0.9rem;
    border-radius: 100px; font-size: 0.72rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.08em;
    margin-bottom: 0.85rem; width: fit-content;
}
.trial-banner-body { flex: 1; }
.trial-banner-body h2 {
    font-size: clamp(1.35rem, 2.5vw, 1.75rem);
    font-weight: 800; line-height: 1.2;
    letter-spacing: -0.02em; margin-bottom: 0.65rem;
}
.trial-banner-body p {
    font-size: 0.92rem; color: var(--text-secondary);
    line-height: 1.7; max-width: 540px; margin-bottom: 1.5rem;
}
.trial-features-row {
    display: flex; flex-wrap: wrap; gap: 0.5rem 1.5rem;
    margin-bottom: 0;
}
.trial-feat {
    display: flex; align-items: center; gap: 0.45rem;
    font-size: 0.85rem; color: var(--text-secondary);
}
.trial-feat .tf-chk {
    color: var(--gold); font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
}
.trial-cta-wrap {
    display: flex; flex-direction: column; align-items: flex-end;
    gap: 0.75rem; flex-shrink: 0; min-width: 210px;
}
.btn-trial {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    color: #0f1115; padding: 0.95rem 2rem;
    border-radius: 12px; font-weight: 800; font-size: 0.92rem;
    text-decoration: none; white-space: nowrap; width: 100%;
    transition: opacity 0.2s, transform 0.2s; border: none; cursor: pointer;
}
.btn-trial:hover { opacity: 0.88; transform: translateY(-2px); color: #0f1115; }
.trial-note {
    font-size: 0.77rem; color: var(--text-muted);
    text-align: center; line-height: 1.5;
}
@media (max-width: 820px) {
    .trial-banner-inner { flex-direction: column; gap: 2rem; padding: 2rem 1.75rem; }
    .trial-cta-wrap { align-items: stretch; width: 100%; min-width: unset; }
    .trial-note { text-align: left; }
}
[dir="rtl"] .trial-banner-inner { flex-direction: row-reverse; }
[dir="rtl"] .trial-features-row { flex-direction: row-reverse; }
[dir="rtl"] .trial-badge-pill { margin-right: 0; }
[dir="rtl"] .trial-cta-wrap { align-items: flex-start; }
[dir="rtl"] .trial-note { text-align: right; }
@media (max-width: 820px) {
    [dir="rtl"] .trial-banner-inner { flex-direction: column; }
    [dir="rtl"] .trial-note { text-align: right; }
}

/* ── RTL overrides ── */
[dir="rtl"] .pr-label::before { display: none; }
[dir="rtl"] .pr-label::after  { content: ''; display: block; width: 18px; height: 2px; background: var(--gold); border-radius: 2px; }
[dir="rtl"] .pack-features li { flex-direction: row-reverse; }
[dir="rtl"] .free-tier-card { flex-direction: row-reverse; }
[dir="rtl"] .tiers-table th,
[dir="rtl"] .tiers-table td { text-align: right; }
[dir="rtl"] .pack-name { flex-direction: row-reverse; justify-content: flex-end; }
[dir="rtl"] .pack-badge { flex-direction: row-reverse; }
[dir="rtl"] .pack-bonus-pill { flex-direction: row-reverse; }
</style>
@endpush

@section('content')
<div class="pricing-page">

    <!-- ═══════════════════════════════════════
         HERO
    ═══════════════════════════════════════ -->
    <div class="pr-hero">
        <div class="pr-hero-inner">
            @if(app()->getLocale() === 'ar')
                <div class="pr-eyebrow" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    أسعار بسيطة وشفافة
                </div>
                <h1 class="pr-hero-h1" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    ادفع فقط مقابل <span>ما تستخدمه</span>
                </h1>
                <p class="pr-hero-lead" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    لا اشتراكات، لا رسوم مقاعد، لا فواتير مفاجئة. اشترِ رصيداً، استخدم الـ API، وادفع مقابل كل رمز ناتج فقط. الرصيد لا تنتهي صلاحيته أبداً.
                </p>
                <div class="pr-free-notice" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    ابدأ مجاناً — 1,000 رصيد عند التسجيل. لا حاجة لبطاقة ائتمان.
                </div>
            @else
                <div class="pr-eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Simple, Transparent Pricing
                </div>
                <h1 class="pr-hero-h1">
                    Pay Only for What You <span>Use</span>
                </h1>
                <p class="pr-hero-lead">
                    No subscriptions, no seat fees, no surprise bills. Buy credits, use the API, pay per output token only. Credits never expire.
                </p>
                <div class="pr-free-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Start free — 1,000 credits on registration. No credit card required.
                </div>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         TRIAL BANNER
    ═══════════════════════════════════════ -->
    <div class="trial-banner fu">
        <div class="trial-banner-inner">
            <div class="trial-banner-body">
                @if(app()->getLocale() === 'ar')
                    <div class="trial-badge-pill" style="font-family:'Tajawal',sans-serif;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        تجربة مجانية
                    </div>
                    <h2 dir="rtl" style="font-family:'Tajawal',sans-serif;">جرّب LLM Resayil مجاناً لمدة 7 أيام</h2>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        ابدأ تجربتك المجانية واستكشف الـ API بالكامل — 1,000 رصيد، جميع النماذج، بدون أي التزام.
                        يتطلب التحقق من البطاقة (0.100 د.ك قابلة للاسترداد). يمكنك الإلغاء في أي وقت خلال فترة التجربة.
                    </p>
                    <div class="trial-features-row" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                        <div class="trial-feat"><span class="tf-chk">✓</span> 1,000 رصيد مجاناً</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> 7 أيام كاملة</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> جميع النماذج 46+</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> مفتاح API مجاني</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> إلغاء في أي وقت</div>
                    </div>
                @else
                    <div class="trial-badge-pill">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        Free Trial
                    </div>
                    <h2>Try LLM Resayil Free for 7 Days</h2>
                    <p>
                        Start a free trial and explore the full API — 1,000 credits, all models, zero commitment.
                        Card verification required (0.100 KWD, refundable). Cancel anytime during the trial period.
                    </p>
                    <div class="trial-features-row">
                        <div class="trial-feat"><span class="tf-chk">✓</span> 1,000 free credits</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> Full 7 days</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> All 46+ models</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> 1 free API key</div>
                        <div class="trial-feat"><span class="tf-chk">✓</span> Cancel anytime</div>
                    </div>
                @endif
            </div>

            <div class="trial-cta-wrap">
                @auth
                    @if(auth()->user()->trial_started_at)
                        {{-- Trial already used — show status --}}
                        @if(app()->getLocale() === 'ar')
                            <div class="btn-trial" style="background:rgba(212,175,55,0.12); color:var(--gold); border:1px solid rgba(212,175,55,0.3); cursor:default; font-family:'Tajawal',sans-serif;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                التجربة نشطة
                            </div>
                            <div class="trial-note" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                تنتهي {{ auth()->user()->trial_started_at->addDays(7)->format('d M Y') }}
                            </div>
                        @else
                            <div class="btn-trial" style="background:rgba(212,175,55,0.12); color:var(--gold); border:1px solid rgba(212,175,55,0.3); cursor:default;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                Trial Active
                            </div>
                            <div class="trial-note">
                                Expires {{ auth()->user()->trial_started_at->addDays(7)->format('d M Y') }}
                            </div>
                        @endif
                    @else
                        {{-- Logged in, no trial yet — link to billing/plans for full payment flow --}}
                        @if(app()->getLocale() === 'ar')
                            <a href="{{ route('billing.plans') }}" class="btn-trial" style="font-family:'Tajawal',sans-serif;">
                                ابدأ التجربة المجانية
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                            </a>
                            <div class="trial-note" dir="rtl" style="font-family:'Tajawal',sans-serif;">رسوم تحقق 0.100 د.ك · قابلة للاسترداد</div>
                        @else
                            <a href="{{ route('billing.plans') }}" class="btn-trial">
                                Start Free Trial
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                            <div class="trial-note">0.100 KWD card verification · refundable</div>
                        @endif
                    @endif
                @else
                    {{-- Guest — send to register first --}}
                    @if(app()->getLocale() === 'ar')
                        <a href="/register" class="btn-trial" style="font-family:'Tajawal',sans-serif;">
                            ابدأ التجربة مجاناً
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        <div class="trial-note" dir="rtl" style="font-family:'Tajawal',sans-serif;">سجّل مجاناً ثم أضف بطاقتك</div>
                    @else
                        <a href="/register" class="btn-trial">
                            Start Free Trial
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                        <div class="trial-note">Register free, then add your card</div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         CREDIT PACKS
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:4rem;">
        <div class="pr-center" style="margin-bottom:3rem;">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">باقات الرصيد</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">اختر باقتك</h2>
                <p class="pr-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">جميع الباقات تتيح الوصول إلى أكثر من 46 نموذجاً. الرصيد مجمّع ومشترك بين جميع مفاتيح API الخاصة بك.</p>
            @else
                <div class="pr-label fu" style="justify-content:center;">Credit Packs</div>
                <h2 class="pr-h2 fu d1">Choose Your Pack</h2>
                <p class="pr-sub fu d2" style="margin:0 auto;">All packs include access to all 46+ models. Credits are pooled and shared across all your API keys.</p>
            @endif
        </div>

        <div class="packs-grid">

            {{-- ══════════════════════════════
                 PACK 1 — STARTER
            ══════════════════════════════ --}}
            <div class="pack-card fu">

                {{-- Header --}}
                <div class="pack-header">
                    @if(app()->getLocale() === 'ar')
                        <div class="pack-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <x-tier-icon tier="starter" :size="20" />
                            الباقة الأساسية
                        </div>
                    @else
                        <div class="pack-name">
                            <x-tier-icon tier="starter" :size="20" />
                            Starter
                        </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="pack-body">
                    {{-- Credits --}}
                    <div class="pack-credits-row">
                        <span class="pack-credits">500</span>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <div class="pack-credits-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد</div>
                    @else
                        <div class="pack-credits-label">Credits</div>
                    @endif

                    <div class="pack-divider"></div>

                    {{-- Price --}}
                    <div class="pack-price-block">
                        @if(app()->getLocale() === 'ar')
                            <div class="pack-price" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                5 <span class="currency">د.ك</span>
                            </div>
                            <div class="pack-per-credit" dir="rtl" style="font-family:'Tajawal',sans-serif;">0.010 د.ك لكل رصيد</div>
                        @else
                            <div class="pack-price">
                                5 <span class="currency">KWD</span>
                            </div>
                            <div class="pack-per-credit">0.010 KWD per credit</div>
                        @endif
                    </div>

                    {{-- CTA --}}
                    @if(app()->getLocale() === 'ar')
                        <a href="/register" class="pack-cta-ghost" style="font-family:'Tajawal',sans-serif;">
                            ابدأ الآن
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                    @else
                        <a href="/register" class="pack-cta-ghost">
                            Get Started
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    @endif

                    {{-- Features --}}
                    @if(app()->getLocale() === 'ar')
                        <ul class="pack-features" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                500 رمز ناتج من API
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                جميع النماذج 46+ مشمولة
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                الرصيد لا تنتهي صلاحيته
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                دفع عبر MyFatoorah بالدينار الكويتي
                            </li>
                        </ul>
                    @else
                        <ul class="pack-features">
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                500 API output tokens
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                All 46+ models included
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                Credits never expire
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                KWD payment via MyFatoorah
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════
                 PACK 2 — GROWTH (BEST VALUE · FEATURED)
            ══════════════════════════════ --}}
            <div class="pack-card featured fu d1">

                {{-- Header --}}
                <div class="pack-header">
                    {{-- Badge — INSIDE the card, no overflow --}}
                    @if(app()->getLocale() === 'ar')
                        <div class="pack-badge badge-value" style="font-family:'Tajawal',sans-serif;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            أفضل قيمة
                        </div>
                        <div class="pack-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <x-tier-icon tier="basic" :size="20" />
                            باقة النمو
                        </div>
                    @else
                        <div class="pack-badge badge-value">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            Best Value
                        </div>
                        <div class="pack-name">
                            <x-tier-icon tier="basic" :size="20" />
                            Growth
                        </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="pack-body">
                    {{-- Credits --}}
                    <div class="pack-credits-row">
                        <span class="pack-credits">1,100</span>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <div class="pack-credits-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد</div>
                        {{-- Bonus pill --}}
                        <div class="pack-bonus-pill" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            بونص 10% رصيد إضافي
                        </div>
                    @else
                        <div class="pack-credits-label">Credits</div>
                        {{-- Bonus pill --}}
                        <div class="pack-bonus-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            +10% Bonus Credits
                        </div>
                    @endif

                    <div class="pack-divider"></div>

                    {{-- Price --}}
                    <div class="pack-price-block">
                        @if(app()->getLocale() === 'ar')
                            <div class="pack-price" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                10 <span class="currency">د.ك</span>
                            </div>
                            <div class="pack-per-credit" dir="rtl" style="font-family:'Tajawal',sans-serif;">0.0091 د.ك لكل رصيد</div>
                        @else
                            <div class="pack-price">
                                10 <span class="currency">KWD</span>
                            </div>
                            <div class="pack-per-credit">0.0091 KWD per credit</div>
                        @endif
                    </div>

                    {{-- CTA (solid gold) --}}
                    @if(app()->getLocale() === 'ar')
                        <a href="/register" class="pack-cta" style="font-family:'Tajawal',sans-serif;">
                            ابدأ الآن
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                    @else
                        <a href="/register" class="pack-cta">
                            Get Started
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    @endif

                    {{-- Features --}}
                    @if(app()->getLocale() === 'ar')
                        <ul class="pack-features" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                1,100 رمز ناتج من API
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                جميع النماذج 46+ مشمولة
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                الرصيد لا تنتهي صلاحيته
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                بونص 10% رصيد إضافي مشمول
                            </li>
                        </ul>
                    @else
                        <ul class="pack-features">
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                1,100 API output tokens
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                All 46+ models included
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                Credits never expire
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                10% bonus credits included
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════
                 PACK 3 — PRO (MOST POPULAR)
            ══════════════════════════════ --}}
            <div class="pack-card popular fu d2">

                {{-- Header --}}
                <div class="pack-header">
                    {{-- Badge — INSIDE the card, no overflow --}}
                    @if(app()->getLocale() === 'ar')
                        <div class="pack-badge badge-popular" style="font-family:'Tajawal',sans-serif;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            الأكثر طلباً
                        </div>
                        <div class="pack-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <x-tier-icon tier="pro" :size="20" />
                            الباقة الاحترافية
                        </div>
                    @else
                        <div class="pack-badge badge-popular">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            Most Popular
                        </div>
                        <div class="pack-name">
                            <x-tier-icon tier="pro" :size="20" />
                            Pro
                        </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="pack-body">
                    {{-- Credits --}}
                    <div class="pack-credits-row">
                        <span class="pack-credits">3,000</span>
                    </div>
                    @if(app()->getLocale() === 'ar')
                        <div class="pack-credits-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">رصيد</div>
                        {{-- Bonus pill --}}
                        <div class="pack-bonus-pill" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            بونص 20% رصيد إضافي
                        </div>
                    @else
                        <div class="pack-credits-label">Credits</div>
                        {{-- Bonus pill --}}
                        <div class="pack-bonus-pill">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            +20% Bonus Credits
                        </div>
                    @endif

                    <div class="pack-divider"></div>

                    {{-- Price --}}
                    <div class="pack-price-block">
                        @if(app()->getLocale() === 'ar')
                            <div class="pack-price" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                                25 <span class="currency">د.ك</span>
                            </div>
                            <div class="pack-per-credit" dir="rtl" style="font-family:'Tajawal',sans-serif;">0.0083 د.ك لكل رصيد</div>
                        @else
                            <div class="pack-price">
                                25 <span class="currency">KWD</span>
                            </div>
                            <div class="pack-per-credit">0.0083 KWD per credit</div>
                        @endif
                    </div>

                    {{-- CTA (ghost style — popular but not featured) --}}
                    @if(app()->getLocale() === 'ar')
                        <a href="/register" class="pack-cta-ghost" style="font-family:'Tajawal',sans-serif;">
                            ابدأ الآن
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                    @else
                        <a href="/register" class="pack-cta-ghost">
                            Get Started
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    @endif

                    {{-- Features --}}
                    @if(app()->getLocale() === 'ar')
                        <ul class="pack-features" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                3,000 رمز ناتج من API
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                جميع النماذج 46+ مشمولة
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                الرصيد لا تنتهي صلاحيته
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                بونص 20% رصيد إضافي مشمول
                            </li>
                        </ul>
                    @else
                        <ul class="pack-features">
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                3,000 API output tokens
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                All 46+ models included
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                Credits never expire
                            </li>
                            <li>
                                <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                                20% bonus credits included
                            </li>
                        </ul>
                    @endif
                </div>
            </div>

        </div>{{-- /.packs-grid --}}

        <!-- Free Tier -->
        <div class="free-tier-card fu">
            <div class="free-tier-icon">
                <x-tier-icon tier="free" :size="32" />
            </div>
            <div class="free-tier-body">
                @if(app()->getLocale() === 'ar')
                    <h3 dir="rtl" style="font-family:'Tajawal',sans-serif;">الفئة المجانية — 1,000 رصيد عند التسجيل</h3>
                    <p dir="rtl" style="font-family:'Tajawal',sans-serif;">كل حساب جديد يحصل على 1,000 رصيد مجاني — دون الحاجة لبطاقة ائتمان. يكفي لاختبار جميع نماذجنا وتجربة الـ API وتقييم ما إذا كان LLM Resayil يناسب احتياجاتك.</p>
                @else
                    <h3>Free Tier — 1,000 Credits on Signup</h3>
                    <p>Every new account receives 1,000 free credits — no credit card required. That is enough to test all models, try the API, and evaluate whether LLM Resayil fits your use case.</p>
                @endif
            </div>
            @if(app()->getLocale() === 'ar')
                <a href="/register" class="btn-free" style="font-family:'Tajawal',sans-serif;">
                    إنشاء حساب مجاني
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </a>
            @else
                <a href="/register" class="btn-free">
                    Create Free Account
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         HOW CREDITS WORK
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem;">
        <div class="pr-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">نموذج الفوترة</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">كيف يعمل نظام الرصيد</h2>
                <p class="pr-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">رصيد واحد = رمز ناتج واحد. لا رسوم على رموز الإدخال، لا رسوم مقاعد، لا تعقيد.</p>
            @else
                <div class="pr-label" style="justify-content:center;">Billing Model</div>
                <h2 class="pr-h2">How Credits Work</h2>
                <p class="pr-sub" style="margin:0 auto;">One credit = one output token. No input token charges, no seat fees, no complexity.</p>
            @endif
        </div>

        <div class="how-credits-flow fu d1">
            @if(app()->getLocale() === 'ar')
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">١</div>
                    <h4>اشترِ رصيداً</h4>
                    <p>اختر باقة الرصيد المناسبة. تُعالج المدفوعات عبر MyFatoorah بالدينار الكويتي (KWD).</p>
                </div>
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">٢</div>
                    <h4>استدعِ الـ API</h4>
                    <p>استخدم مفتاح API الخاص بك مع أي SDK متوافق مع OpenAI أو عميل HTTP.</p>
                </div>
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">٣</div>
                    <h4>خصم الرصيد</h4>
                    <p>بعد كل استجابة، يُحسب عدد الرموز الناتجة ويُخصم الرصيد المقابل تلقائياً.</p>
                </div>
                <div class="hcf-step" dir="rtl" style="font-family:'Tajawal',sans-serif;">
                    <div class="hcf-num">٤</div>
                    <h4>تتبّع الاستخدام</h4>
                    <p>تعرض لوحة التحكم الرصيد الفعلي، وسجل الاستخدام، وتفاصيل تكلفة كل طلب.</p>
                </div>
            @else
                <div class="hcf-step">
                    <div class="hcf-num">1</div>
                    <h4>Buy Credits</h4>
                    <p>Choose a credit pack. Payment processed via MyFatoorah in Kuwaiti Dinar (KWD).</p>
                </div>
                <div class="hcf-step">
                    <div class="hcf-num">2</div>
                    <h4>Call the API</h4>
                    <p>Use your API key with any OpenAI-compatible SDK or HTTP client. Same interface, lower cost.</p>
                </div>
                <div class="hcf-step">
                    <div class="hcf-num">3</div>
                    <h4>Credits Deducted</h4>
                    <p>After each response, output tokens are counted and credits deducted from your balance automatically.</p>
                </div>
                <div class="hcf-step">
                    <div class="hcf-num">4</div>
                    <h4>Track Usage</h4>
                    <p>Your dashboard shows real-time balance, usage history, and per-request cost breakdown.</p>
                </div>
            @endif
        </div>

        <div style="margin-top:1.5rem; text-align:center;">
            @if(app()->getLocale() === 'ar')
                <a href="/credits" style="color:var(--gold); font-size:0.88rem; font-weight:600; font-family:'Tajawal',sans-serif;" dir="rtl">اقرأ الدليل الكامل للرصيد &larr;</a>
            @else
                <a href="/credits" style="color:var(--gold); font-size:0.88rem; font-weight:600;">Read the full credits guide &rarr;</a>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         MODEL TIERS TABLE
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem;">
        <div class="pr-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">التسعير حسب النموذج</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">نظام ضرب الرصيد</h2>
                <p class="pr-sub" dir="rtl" style="font-family:'Tajawal',sans-serif; margin:0 auto;">الرصيد المحسوب لكل رمز ناتج. رموز الإدخال مجانية دائماً.</p>
            @else
                <div class="pr-label" style="justify-content:center;">Pricing by Model</div>
                <h2 class="pr-h2">Credit Multiplier System</h2>
                <p class="pr-sub" style="margin:0 auto;">Credits charged per output token. Input tokens are always free.</p>
            @endif
        </div>

        <div class="multiplier-grid">
            {{-- Standard Models Card --}}
            <div class="multiplier-card fu d1">
                <div class="multiplier-card-header">
                    <x-tier-icon tier="starter" :size="28" />
                    @if(app()->getLocale() === 'ar')
                        <div class="multiplier-card-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">النماذج القياسية</div>
                    @else
                        <div class="multiplier-card-label">Standard Models</div>
                    @endif
                </div>
                @if(app()->getLocale() === 'ar')
                    <p class="multiplier-card-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">نماذج مفتوحة المصدر خفيفة وفعّالة</p>
                @else
                    <p class="multiplier-card-desc">Lightweight &amp; efficient open-source models</p>
                @endif

                <div class="multiplier-size-rows">
                    <div class="multiplier-size-row">
                        <div class="multiplier-size-left">
                            <span class="multiplier-size-icon"><svg width="18" height="18" viewBox="0 0 32 32" fill="none" aria-hidden="true" style="flex-shrink:0;"><circle cx="16" cy="16" r="10" stroke="#6b7280" stroke-width="1.5" fill="rgba(107,114,128,0.07)"/><ellipse cx="16" cy="16" rx="10" ry="4.5" stroke="#6b7280" stroke-width="1" fill="none" opacity="0.5"/><circle cx="16" cy="16" r="2.5" fill="#6b7280" fill-opacity="0.9"/></svg></span>
                            <div>
                                @if(app()->getLocale() === 'ar')
                                    <div class="multiplier-size-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">صغير</div>
                                    <div class="multiplier-size-params">3–14B</div>
                                @else
                                    <div class="multiplier-size-name">Small</div>
                                    <div class="multiplier-size-params">3–14B</div>
                                @endif
                            </div>
                        </div>
                        <div class="multiplier-cost">0.5 cr <span>/ token</span></div>
                    </div>
                    <div class="multiplier-size-row">
                        <div class="multiplier-size-left">
                            <span class="multiplier-size-icon"><svg width="18" height="18" viewBox="0 0 32 32" fill="none" aria-hidden="true" style="flex-shrink:0;"><path d="M16 6l7 7-7 13-7-13z" fill="rgba(129,140,248,0.12)" stroke="#818cf8" stroke-width="1.5" stroke-linejoin="round"/><path d="M9 13h14" stroke="#818cf8" stroke-width="1" opacity="0.45"/></svg></span>
                            <div>
                                @if(app()->getLocale() === 'ar')
                                    <div class="multiplier-size-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">متوسط</div>
                                    <div class="multiplier-size-params">20–30B</div>
                                @else
                                    <div class="multiplier-size-name">Medium</div>
                                    <div class="multiplier-size-params">20–30B</div>
                                @endif
                            </div>
                        </div>
                        <div class="multiplier-cost">1.5 cr <span>/ token</span></div>
                    </div>
                </div>
            </div>

            {{-- Frontier Models Card --}}
            <div class="multiplier-card frontier fu d2">
                <div class="multiplier-card-header">
                    <x-tier-icon tier="pro" :size="28" />
                    @if(app()->getLocale() === 'ar')
                        <div class="multiplier-card-label" dir="rtl" style="font-family:'Tajawal',sans-serif;">النماذج المتقدمة</div>
                    @else
                        <div class="multiplier-card-label">Frontier Models</div>
                    @endif
                </div>
                @if(app()->getLocale() === 'ar')
                    <p class="multiplier-card-desc" dir="rtl" style="font-family:'Tajawal',sans-serif;">نماذج الذكاء الاصطناعي الأحدث والأكثر قدرة</p>
                @else
                    <p class="multiplier-card-desc">State-of-the-art reasoning &amp; intelligence models</p>
                @endif

                <div class="multiplier-size-rows">
                    <div class="multiplier-size-row">
                        <div class="multiplier-size-left">
                            <span class="multiplier-size-icon"><svg width="18" height="18" viewBox="0 0 32 32" fill="none" aria-hidden="true" style="flex-shrink:0;"><path d="M16 6l7 7-7 13-7-13z" fill="rgba(129,140,248,0.12)" stroke="#818cf8" stroke-width="1.5" stroke-linejoin="round"/><path d="M9 13h14" stroke="#818cf8" stroke-width="1" opacity="0.45"/></svg></span>
                            <div>
                                @if(app()->getLocale() === 'ar')
                                    <div class="multiplier-size-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">متوسط</div>
                                    <div class="multiplier-size-params">12–30B &mdash; GLM-4، Qwen3-30B، Mistral-Large</div>
                                @else
                                    <div class="multiplier-size-name">Medium</div>
                                    <div class="multiplier-size-params">12–30B &mdash; GLM-4, Qwen3-30B, Mistral-Large</div>
                                @endif
                            </div>
                        </div>
                        <div class="multiplier-cost">2.5 cr <span>/ token</span></div>
                    </div>
                    <div class="multiplier-size-row">
                        <div class="multiplier-size-left">
                            <span class="multiplier-size-icon"><svg width="18" height="18" viewBox="0 0 32 32" fill="none" aria-hidden="true" style="flex-shrink:0;"><path d="M16 5l2.6 7.7H27l-6.7 4.9 2.6 7.7-6.9-5-6.9 5 2.6-7.7L5 12.7h8.4z" fill="rgba(212,175,55,0.15)" stroke="#d4af37" stroke-width="1.5" stroke-linejoin="round"/><circle cx="16" cy="16" r="2" fill="#d4af37" fill-opacity="0.8"/></svg></span>
                            <div>
                                @if(app()->getLocale() === 'ar')
                                    <div class="multiplier-size-name" dir="rtl" style="font-family:'Tajawal',sans-serif;">كبير</div>
                                    <div class="multiplier-size-params">70B–671B &mdash; DeepSeek-V3، Llama3.1:70B، Qwen3.5:397B</div>
                                @else
                                    <div class="multiplier-size-name">Large</div>
                                    <div class="multiplier-size-params">70B–671B &mdash; DeepSeek-V3, Llama3.1:70B, Qwen3.5:397B</div>
                                @endif
                            </div>
                        </div>
                        <div class="multiplier-cost">3.5 cr <span>/ token</span></div>
                    </div>
                </div>
            </div>
        </div>

        <p class="multiplier-note fu d3">
            @if(app()->getLocale() === 'ar')
                <span dir="rtl" style="font-family:'Tajawal',sans-serif;">&#x2713; رموز الإدخال مجانية دائمًا — تدفع فقط مقابل ما تُنتجه.</span>
            @else
                &#x2713; Input tokens are always free &mdash; you only pay for what you generate.
            @endif
        </p>
    </section>

    <!-- ═══════════════════════════════════════
         FAQ ACCORDION
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem;">
        <div class="pr-center fu">
            @if(app()->getLocale() === 'ar')
                <div class="pr-label" dir="rtl" style="font-family:'Tajawal',sans-serif; justify-content:center;">الأسئلة الشائعة</div>
                <h2 class="pr-h2" dir="rtl" style="font-family:'Tajawal',sans-serif;">أسئلة شائعة</h2>
            @else
                <div class="pr-label" style="justify-content:center;">FAQ</div>
                <h2 class="pr-h2">Common Questions</h2>
            @endif
        </div>

        <div class="faq-list">
            @if(app()->getLocale() === 'ar')
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل تنتهي صلاحية الرصيد؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">لا. الرصيد لا تنتهي صلاحيته أبداً. اشترِ مرة واحدة واستخدمه متى تشاء — لا يوجد أي ضغط زمني.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل تُحسب رموز الإدخال؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">لا. يُحسب الرصيد على رموز الإخراج فقط. مطالباتك ونافذة السياق مجانية تماماً.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">ما العملة المستخدمة؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">جميع الأسعار بالدينار الكويتي (KWD). تُعالج المدفوعات بأمان عبر MyFatoorah.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل يمكنني استخدام مفاتيح API متعددة من نفس الرصيد؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">نعم. جميع مفاتيح API في حسابك تشترك في نفس رصيد الاعتمادات. أنشئ عدداً غير محدود من المفاتيح حسب حاجتك.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false" dir="rtl">
                        <span class="faq-q" style="font-family:'Tajawal',sans-serif;">هل الـ API متوافق مع OpenAI؟</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p dir="rtl" style="font-family:'Tajawal',sans-serif;">نعم. يتبع الـ API تنسيق OpenAI. أضفه إلى كودك الحالي بتغيير رابط الـ base URL ومفتاح API فقط. راجع <a href="/docs">التوثيق</a> للمزيد من الأمثلة.</p></div>
                </div>
            @else
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Do credits expire?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>No. Credits never expire. Buy once, use whenever you need — no time pressure whatsoever.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Are input tokens charged?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>No. Only output tokens are charged. Your prompts and context window are completely free.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">What currency is used?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>All pricing is in Kuwaiti Dinar (KWD). Payments are processed securely via MyFatoorah.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Can I use multiple API keys from one balance?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>Yes. All API keys on your account share the same credit balance. Create as many keys as you need for different projects or applications.</p></div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" aria-expanded="false">
                        <span class="faq-q">Is this OpenAI compatible?</span>
                        <div class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg></div>
                    </button>
                    <div class="faq-body"><p>Yes. The API follows the OpenAI format exactly. Drop into your existing code by changing the base URL and API key only. See <a href="/docs">the documentation</a> for examples.</p></div>
                </div>
            @endif
        </div>
    </section>

    <!-- ═══════════════════════════════════════
         FINAL CTA BAND
    ═══════════════════════════════════════ -->
    <section class="pr-section" style="padding-top:1rem; padding-bottom:4rem;">
        <div class="pr-cta-band fu">
            @if(app()->getLocale() === 'ar')
                <h2 dir="rtl" style="font-family:'Tajawal',sans-serif;">ابدأ البناء اليوم</h2>
                <p dir="rtl" style="font-family:'Tajawal',sans-serif;">سجّل مجاناً، احصل على 1,000 رصيد فوراً، وأجرِ أول استدعاء API في أقل من 5 دقائق.</p>
                <div class="pr-cta-btns">
                    <a href="/register" class="btn-cta-dark" style="font-family:'Tajawal',sans-serif;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                        ابدأ مجاناً
                    </a>
                    <a href="/docs" class="btn-cta-outline" style="font-family:'Tajawal',sans-serif;">عرض توثيق الـ API</a>
                </div>
            @else
                <h2>Start Building Today</h2>
                <p>Register for free, get 1,000 credits instantly, and connect your first API call in under 5 minutes.</p>
                <div class="pr-cta-btns">
                    <a href="/register" class="btn-cta-dark">
                        Get Started Free
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <a href="/docs" class="btn-cta-outline">View API Docs</a>
                </div>
            @endif
        </div>
    </section>

</div>

<script>
(function () {
    'use strict';

    /* FAQ accordion */
    document.querySelectorAll('.faq-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var body = item.querySelector('.faq-body');
            var isOpen = item.classList.contains('open');

            document.querySelectorAll('.faq-item.open').forEach(function (el) {
                el.classList.remove('open');
                el.querySelector('.faq-body').classList.remove('open');
                el.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
            });

            if (!isOpen) {
                item.classList.add('open');
                body.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* Scroll-reveal */
    var fus = document.querySelectorAll('.fu');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('on'); io.unobserve(en.target); }
            });
        }, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
        fus.forEach(function (el) { io.observe(el); });
    } else {
        fus.forEach(function (el) { el.classList.add('on'); });
    }
})();
</script>
@endsection
