# Requirements: v1.2 - SEO Optimization

**Defined:** 2026-03-06
**Core Value:** Improve organic search visibility from 62/100 → 85+/100, establish authority as OpenAI alternative, enable AI search presence

---

## v1.2 Requirements

### Structured Data (Schema Markup)

- [ ] **SCHEMA-01**: Add Organization schema to page header (name, logo, URL, description, address)
- [ ] **SCHEMA-02**: Add SoftwareApplication schema to homepage (description, category, offers, ratings)
- [ ] **SCHEMA-03**: Add FAQPage schema with 10+ FAQ entries on /faq page
- [ ] **SCHEMA-04**: Add breadcrumb schema to /docs subsections (path structure)
- [ ] **SCHEMA-05**: Add SoftwareApplication Offer schema for pricing tiers

### Metadata & On-Page SEO

- [ ] **META-01**: Add unique meta descriptions to all 20 pages (currently 50% missing)
- [ ] **META-02**: Add OpenGraph meta tags (og:title, og:description, og:image, og:url) to all pages
- [ ] **META-03**: Add Twitter Card meta tags (twitter:card, twitter:title, twitter:description, twitter:image)
- [ ] **TITLE-01**: Standardize page titles to format: [Primary Keyword] - LLM Resayil
- [ ] **CANON-01**: Add canonical tags to all pages (self-referential)

### Technical SEO & Crawlability

- [ ] **ROBOT-01**: Update robots.txt to allow /pricing, /features, /use-cases for AI crawlers (GPTBot, ClaudeBot, PerplexityBot)
- [ ] **HREFL-01**: Implement hreflang tags for EN/AR language versions on all pages
- [ ] **IMAGE-01**: Convert hero background images to <img> tags with semantic alt text
- [ ] **IMAGE-02**: Add lazy loading to images below fold
- [ ] **PERF-01**: Optimize Core Web Vitals — LCP <2.5s, INP <200ms, CLS <0.1

### Content Expansion & Authority

- [ ] **DOCS-01**: Expand /docs from 737 → 2,500+ words with authentication, endpoints, error handling, rate limits, code examples
- [ ] **DOCS-02**: Add request/response examples (cURL, Python, JavaScript) to all API endpoints
- [ ] **LINK-01**: Audit internal link structure; add 50+ contextual links with keyword-rich anchor text
- [ ] **LINK-02**: Create content clusters linking related pages (docs → pricing → register)

### E-E-A-T Signals (Expertise, Experience, Authority, Trustworthiness)

- [ ] **EEAT-01**: Create team/about page with founder + team member bios (expertise, credentials, LinkedIn)
- [ ] **EEAT-02**: Add 5–10 customer testimonials with photo, name, company, and attributed quotes
- [ ] **EEAT-03**: Create 2–3 case studies with quantified results (usage metrics, cost savings, performance gains)
- [ ] **EEAT-04**: Showcase integration ecosystem (partnerships, model providers, APIs supported)
- [ ] **COMP-01**: Create competitor comparison page (vs OpenAI, Claude, Ollama, Together AI)
- [ ] **USE-01**: Create use cases section covering industry verticals (fintech, healthcare, e-commerce, education)
- [ ] **TRUST-01**: Add security badges/certifications (HTTPS, privacy policy, data protection compliance)

### FAQ & Feature Discovery

- [ ] **FAQ-01**: Create /faq page with 10+ Q&A entries covering: pricing, models, API usage, billing, regions, compliance
- [ ] **FEAT-01**: Create /features page highlighting model diversity, pricing, speed, reliability, compliance

### AI Search & Knowledge Graph

- [ ] **AI-01**: Configure structured data for Google SGE/Perplexity/Claude AI recognition
- [ ] **AI-02**: Ensure /docs, /pricing, /about are crawlable by AI bots (robots.txt allows them)
- [ ] **BRAND-01**: Add Organization name, URL, and brand signals for knowledge graph inclusion

---

## Future Requirements (v1.3+)

- **BLOG-01**: Launch blog with 50+ technical articles on LLM topics
- **VIDEO-01**: Add product video walkthrough to homepage
- **BACKLINK-01**: Execute outreach strategy for high-authority backlinks
- **LOCAL-01**: Add LocalBusiness schema (if supporting physical office in Kuwait)
- **AUTHOR-01**: Create author schema for blog posts
- **REVIEW-01**: Integrate third-party reviews (Trustpilot, G2, Capterra)

---

## Out of Scope (v1.2)

| Feature | Reason |
|---------|--------|
| Paid link acquisition | Focus on earned links first; organic growth sustainable |
| Migration to headless CMS | Current Laravel stack sufficient; optimize existing |
| Blog platform launch | Content distribution > platform launch; defer to v1.3 |
| SEO tools integration | GSC + Analytics sufficient for monitoring |
| Paid ads (SEM) | Organic optimization is priority; SEM later if needed |

---

## Traceability

| Requirement | Phase | Status |
|-------------|-------|--------|
| SCHEMA-01 | Phase 10 | Pending |
| SCHEMA-02 | Phase 10 | Pending |
| SCHEMA-03 | Phase 10 | Pending |
| SCHEMA-04 | Phase 11 | Pending |
| SCHEMA-05 | Phase 12 | Pending |
| META-01 | Phase 10 | Pending |
| META-02 | Phase 10 | Pending |
| META-03 | Phase 10 | Pending |
| TITLE-01 | Phase 10 | Pending |
| CANON-01 | Phase 10 | Pending |
| ROBOT-01 | Phase 10 | Pending |
| HREFL-01 | Phase 11 | Pending |
| IMAGE-01 | Phase 11 | Pending |
| IMAGE-02 | Phase 11 | Pending |
| PERF-01 | Phase 13 | Pending |
| DOCS-01 | Phase 11 | Pending |
| DOCS-02 | Phase 11 | Pending |
| LINK-01 | Phase 11 | Pending |
| LINK-02 | Phase 11 | Pending |
| EEAT-01 | Phase 12 | Pending |
| EEAT-02 | Phase 12 | Pending |
| EEAT-03 | Phase 12 | Pending |
| EEAT-04 | Phase 12 | Pending |
| COMP-01 | Phase 12 | Pending |
| USE-01 | Phase 12 | Pending |
| TRUST-01 | Phase 12 | Pending |
| FAQ-01 | Phase 11 | Pending |
| FEAT-01 | Phase 11 | Pending |
| AI-01 | Phase 10 | Pending |
| AI-02 | Phase 10 | Pending |
| BRAND-01 | Phase 10 | Pending |

**Coverage:**
- v1.2 requirements: 31 total
- Mapped to phases: 31
- Unmapped: 0 ✓

---

*Requirements defined: 2026-03-06 from SEO audit findings*
