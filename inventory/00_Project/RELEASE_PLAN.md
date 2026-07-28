# BUSINESSOS NEPAL - RELEASE PLAN

**Version:** 1.0  
**Date:** July 25, 2026  
**Status:** Approved  
**Document Type:** Release Plan  
**Related Documents:** [Project Roadmap v1.0](./PROJECT_ROADMAP.md), [Project Vision v1.1](./PROJECT_VISION.md), [CHANGELOG.md](./CHANGELOG.md)

---

## 1. PURPOSE

This document outlines the **complete go-to-market strategy** for the BusinessOS Nepal Version 1.0 (V1) launch. 

It details the precise checklist of activities required **before, during, and after** the public release to ensure:
- Zero data loss during deployment.
- Maximum visibility among the target SME audience.
- Smooth onboarding experience for first-time users.
- Rapid response to post-launch feedback.

This plan assumes a **bootstrapped / free-of-cost** model, meaning heavy paid advertising is not part of V1. Instead, we rely on organic growth, community engagement, and word-of-mouth.

---

## 2. SCOPE

### 2.1 In Scope
- Pre-Launch activities (testing, documentation, seeding, domain setup).
- Launch Day activities (deployment, announcements, monitoring).
- Post-Launch activities (support, hotfixes, feedback collection, version 1.0.1 planning).

### 2.2 Out of Scope
- V2 feature planning (handled in `FUTURE_MODULES.md`).
- Long-term SEO strategy.
- Investor relations (since self-funded).

---

## 3. OBJECTIVES

| # | Objective | Alignment |
|---|-----------|-----------|
| R1 | **Ensure a flawless deployment**—no downtime, no data loss, no broken links. | Trust |
| R2 | **Achieve 100 registrations** within the first 30 days post-launch. | Growth |
| R3 | **Achieve a 4.5/5 star rating** on user feedback forms (simplicity + speed). | Quality |
| R4 | **Establish a routine** for monthly minor releases (v1.1, v1.2). | Sustainability |

---

## 4. DETAILED SECTIONS

### 4.1 Release Timeline

| Phase | Date | Activities |
|-------|------|------------|
| **Pre-Launch (T-30 days)** | March 1, 2027 | Phase 7 begins. Security hardening, performance tuning, final UAT with 10 beta companies. |
| **Pre-Launch (T-14 days)** | March 15, 2027 | Finalize all documentation (Help Center, FAQs). Prepare promotional materials. Set up billing (manual/Stripe). |
| **Pre-Launch (T-7 days)** | March 24, 2027 | Soft Launch / "Friends & Family" testing. Ensure email delivery, SSL, and deployment scripts work perfectly. |
| **Launch Day (T-0)** | **April 1, 2027 (Tentative)** | Public announcement. Production deployment. Go-live. |
| **Post-Launch (T+1 day)** | April 2, 2027 | Collect logs, monitor errors, assist early registrants. |
| **Post-Launch (T+7 days)** | April 8, 2027 | Bug-fix release (v1.0.1) if critical issues found. |
| **Post-Launch (T+30 days)** | May 1, 2027 | First feature release (v1.1.0) based on initial feedback. |

---

### 4.2 Pre-Launch Checklist (March 1 – March 31, 2027)

| # | Task | Status (Check) | Owner |
|---|------|----------------|-------|
| 1 | **Environment Setup** | [ ] | Dev |
|   | - Production server provisioned (VPS). | | |
|   | - Domain `businessos.com.np` (or .com) pointed to server. | | |
|   | - SSL certificate installed (Let's Encrypt). | | |
|   | - Database (MySQL) on production. | | |
| 2 | **Deployment Automation** | [ ] | Dev |
|   | - GitHub Actions or Laravel Envoy script tested. | | |
|   | - Zero-downtime deployment strategy (symbolic link). | | |
|   | - Backups configured (Spatie Backup) to remote storage. | | |
| 3 | **Production Data Seeding** | [ ] | Dev |
|   | - Seed default Roles & Permissions. | | |
|   | - Seed default Categories, Brands, Units. | | |
|   | - Create default "Admin" account for demo. | | |
| 4 | **Documentation** | [ ] | Dev/BA |
|   | - User Manual (How to use POS, Inventory). | | |
|   | - FAQ page. | | |
|   | - Video walkthrough (screencast). | | |
| 5 | **Marketing Assets** | [ ] | Dev |
|   | - Public Landing Page (HTML prototype) is deployed. | | |
|   | - Social media posts drafted (LinkedIn, Facebook, Twitter). | | |
|   | - Email list of 10 beta testers ready. | | |
| 6 | **Payment/Subscription** | [ ] | Dev |
|   | - Free Tier defined (Max Users: 2, Max Products: 100, Max Branches: 1). | | |
|   | - Paid Tier plans created (Manual invoicing or Stripe/eSewa integration). | | |
|   | - Test subscription purchase/activation. | | |
| 7 | **Performance Check** | [ ] | Dev |
|   | - Load test: Simulate 50 concurrent users on POS. | | |
|   | - Check Laravel Debugbar for N+1 queries. | | |
|   | - Ensure all images are optimized (< 100KB). | | |
| 8 | **Final UAT (Beta)** | [ ] | Beta Users |
|   | - 10 beta companies operate for 2 weeks on staging server. | | |
|   | - Collect bug reports and fix critical ones before launch. | | |

---

### 4.3 Launch Day Checklist (April 1, 2027)

| Time (NST) | Task | Owner | Status |
|------------|------|-------|--------|
| **12:00 AM** | **Freeze Code**: No more commits to `main` until launch is complete. | Dev | [ ] |
| **6:00 AM** | **Run Deployment Script**: Switch production to latest `main` branch. | Dev | [ ] |
| **6:15 AM** | **Run Migrations**: `php artisan migrate --force` (ensure backup taken first). | Dev | [ ] |
| **6:30 AM** | **Clear Caches**: `php artisan optimize:clear`; then `php artisan optimize`. | Dev | [ ] |
| **7:00 AM** | **Smoke Testing**: Manually test Login → Product Add → Sale → Logout. | Dev | [ ] |
| **7:30 AM** | **Check Monitoring**: Enable Laravel Horizon (if queues) / Telescope. | Dev | [ ] |
| **8:00 AM** | **Flip the Switch**: Set `APP_ENV=production` in `.env`. | Dev | [ ] |
| **8:15 AM** | **Verify SSL**: HTTPS is working everywhere. No mixed content warnings. | Dev | [ ] |
| **9:00 AM** | **Launch Announcement**: |
|   | - Post on LinkedIn/Facebook. | Dev | [ ] |
|   | - Send email to beta testers (invite them to use live production). | Dev | [ ] |
|   | - Update ProductHunt (optional). | Dev | [ ] |
| **10:00 AM** | **Monitor Logs**: `tail -f storage/logs/laravel.log` for errors. | Dev | [ ] |
| **12:00 PM** | **First User Registration**: Help them onboard. | Dev | [ ] |
| **6:00 PM** | **End of Day Check**: Summary of registrations, errors, performance. | Dev | [ ] |

---

### 4.4 Post-Launch Checklist (T+1 to T+30)

| # | Task | Timeline | Owner |
|---|------|----------|-------|
| 1 | **Monitor Error Tracking**: Use Laravel Telescope / Sentry to capture exceptions. | Daily | Dev |
| 2 | **Collect Feedback**: Send a feedback form to all new registrations after 3 days of usage. | T+3 | Dev |
| 3 | **Hotfix Process**: If a critical bug (data loss, security) appears, release a patch immediately (v1.0.1) without waiting for monthly cycle. | As needed | Dev |
| 4 | **Review Analytics**: Track which modules are used most. This informs V1.1 priority. | T+14 | Dev |
| 5 | **Community Building**: Create a Facebook Group / Slack channel for BusinessOS users. | T+7 | Dev |
| 6 | **Version 1.1 Planning**: Based on feedback, start drafting the next minor release. | T+30 | Dev |

---

### 4.5 Rollback Plan

In case of catastrophic failure during launch (e.g., database corruption, massive performance degradation):

| Step | Action |
|------|--------|
| 1. | **Immediately** revert to the previous release by switching the symbolic link to the old build. |
| 2. | Restore the database from the backup taken immediately before migration. |
| 3. | Set `APP_DEBUG=true` temporarily on production (for diagnostic purposes only). |
| 4. | Investigate the root cause. |
| 5. | Fix the issue, test locally, and schedule a second deployment attempt (maybe next day). |

---

## 5. BEST PRACTICES

1. **Don't Launch on a Monday**: Avoid Monday blues. Launch on a Friday or Saturday so if something breaks, you have the weekend to fix it without affecting too many business hours.
2. **Keep a "War Room" Channel**: Create a dedicated WhatsApp/Discord group with the team for live incident reporting during the first 48 hours.
3. **Celebrate**: This is a massive achievement. Take a break after T+3 days (once stable).
4. **Document Everything**: Record the exact commands used for deployment so future launches are identical.

---

## 6. RISKS & MITIGATIONS (Release Specific)

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| **DDoS / Bot traffic** | Low | Medium | Set up Cloudflare (free tier) for DDoS protection. |
| **Email delivery failure** | Medium | High | Use a transactional email service (SendGrid / SES) instead of `mail()` function. |
| **Payment gateway downtime** | Low | Medium | If using Stripe/eSewa, have a "Manual Invoice" fallback option for the first week. |
| **Negative User Feedback** | High | Low | Have a "Feature Request" board ready. Be transparent about V1 limitations. |

---

## 7. RECOMMENDATIONS

1. **Soft Launch First**: Consider a "soft launch" where you announce it quietly on LinkedIn to get 10-20 users before the loud public launch.
2. **Create a "Getting Started" video**: A 5-minute YouTube video showing how to set up Inventory & POS reduces support calls by 70%.
3. **Free Tier Strategy**: Keep the Free Tier forever (limited to 100 products, 1 user) so any business can try it without risk.
4. **Monitor Server Costs**: Since it's free-to-build, ensure you don't overspend on hosting. Use `uptime` monitoring tools to auto-scale only when necessary.

---

## 8. REVISION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-07-25 | BusinessOS Team | Initial creation. Defined pre-launch, launch day, and post-launch checklists. |

---

**Document Status:** ✅ Ready for Execution (Follow during March 2027)

---

## 9. CONCLUSION

BusinessOS Nepal V1 launch is scheduled for **April 1, 2027**. 

By following this release plan meticulously, we will ensure:
- A **smooth, professional deployment** that instills confidence in our first users.
- A **structured feedback loop** that directly feeds into the next version.
- A **sustainable development rhythm** (build → launch → refine → repeat).

Good luck, and see you on the other side of launch! 🚀