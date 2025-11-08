# BCN WordPress Theme Development Plan

## 🎯 Project Overview
**Goal:** Create a robust, maintainable foundation for a custom marketing and community platform for Buffalo Cannabis Network (BCN).

## 📋 Current Status
- ✅ Design system with CSS variables
- ✅ Internal linking system
- ✅ Basic front-page template
- ✅ About page template
- ⚠️ Incomplete template structure
- ❌ Missing custom post types
- ❌ Missing SEO optimization
- ❌ Missing performance optimization

## 🗺️ Site Architecture & SEO Strategy

### Primary Pages (Tier 1 - High Priority)
```
Home (/)
├── About BCN (/about/)
├── Membership (/membership/)
├── Events (/events/)
├── News (/news/)
└── Contact (/contact/)
```

### Secondary Pages (Tier 2 - Medium Priority)
```
Resources (/resources/)
├── Member Directory (/members/directory/)
├── Advocacy (/advocacy/)
├── Community (/community/)
└── FAQ (/faq/)
```

### Content Hubs (Tier 3 - Industry Focus)
```
Cultivation (/cultivation/)
Processing (/processing/)
Retail (/retail/)
Ancillary Services (/ancillary/)
```

### Custom Post Types
```
Events (bcn_event)
├── Single Event (/events/event-slug/)
├── Archive (/events/)
└── Taxonomy: Event Type (/events/type/workshop/)

News (bcn_news)
├── Single News (/news/article-slug/)
├── Archive (/news/)
└── Taxonomy: News Category (/news/category/industry-updates/)

Members (bcn_member)
├── Single Member (/members/member-slug/)
├── Archive (/members/)
└── Member Directory (/members/directory/)
```

## 🏗️ Development Phases

### Phase 1: Foundation & Core Templates (Week 1-2)
**Priority: CRITICAL**

#### 1.1 Design System Integration
- [ ] Integrate design system into functions.php
- [ ] Create component library CSS
- [ ] Set up typography system
- [ ] Test responsive breakpoints

#### 1.2 Core Template Structure
- [ ] `front-page.php` - Complete with all sections
- [ ] `single-news.php` - News article template
- [ ] `archive-news.php` - News listing
- [ ] `taxonomy-news_category.php` - News category pages
- [ ] `single-events.php` - Event detail template
- [ ] `archive-events.php` - Events listing
- [ ] `404.php` - Custom 404 page
- [ ] `page-members-portal.php` - Members dashboard

#### 1.3 Custom Post Types & Taxonomies
- [ ] Events CPT with meta fields
- [ ] News CPT with meta fields
- [ ] Event Type taxonomy
- [ ] News Category taxonomy
- [ ] Member CPT (if needed)

### Phase 2: SEO & Performance (Week 2-3)
**Priority: HIGH**

#### 2.1 SEO Foundation
- [ ] Schema markup for events, news, organization
- [ ] Open Graph meta tags
- [ ] Twitter Card meta tags
- [ ] XML sitemap integration
- [ ] Breadcrumb navigation
- [ ] Internal linking strategy implementation

#### 2.2 Performance Optimization
- [ ] Image optimization system
- [ ] CSS/JS minification
- [ ] Lazy loading implementation
- [ ] Critical CSS extraction
- [ ] Database query optimization

#### 2.3 Analytics & Tracking
- [ ] Google Analytics 4 integration
- [ ] Google Search Console setup
- [ ] Event tracking for conversions
- [ ] Member engagement tracking

### Phase 3: Advanced Features (Week 3-4)
**Priority: MEDIUM**

#### 3.1 Layout Patterns & Components
- [ ] Hero section variants
- [ ] Card grid layouts
- [ ] CTA sections
- [ ] Member/Sponsor logo wall
- [ ] Accordion/toggle components
- [ ] Search functionality

#### 3.2 Member Portal Features
- [ ] User authentication system
- [ ] Member profile management
- [ ] Event registration system
- [ ] Resource access controls
- [ ] Member directory with filters

#### 3.3 Content Management
- [ ] ACF Pro field groups
- [ ] Customizer options
- [ ] Admin interface improvements
- [ ] Content workflow tools

### Phase 4: Testing & Launch (Week 4-5)
**Priority: HIGH**

#### 4.1 Quality Assurance
- [ ] Cross-browser testing
- [ ] Mobile responsiveness testing
- [ ] Performance testing
- [ ] SEO audit
- [ ] Accessibility testing

#### 4.2 Launch Preparation
- [ ] Staging environment setup
- [ ] Content migration
- [ ] User training
- [ ] Documentation
- [ ] Launch checklist

## 🔗 Internal Linking Strategy

### Link Architecture
```
Home Page
├── Links to: About, Membership, Events, News
├── CTAs: Join BCN, View Events
└── Featured: Latest News, Upcoming Events

About Page
├── Links to: Membership, Events, Advocacy, Community
├── CTAs: Join BCN, Contact Us
└── Related: Leadership, Values, Mission

Events Page
├── Links to: Membership, News, Member Directory
├── CTAs: Join BCN, Register for Event
└── Related: Past Events, Event Categories

News Page
├── Links to: Events, Advocacy, Community
├── CTAs: Join BCN, Subscribe
└── Related: Industry Updates, Member News

Membership Page
├── Links to: Events, Member Directory, Resources
├── CTAs: Join Now, Contact Us
└── Related: Benefits, Pricing, FAQ
```

### SEO Link Strategy
- **Anchor Text Variety:** Use descriptive, keyword-rich anchor text
- **Contextual Linking:** Link within relevant content sections
- **Footer Navigation:** Comprehensive site-wide navigation
- **Breadcrumbs:** Clear page hierarchy
- **Related Content:** Cross-linking between related pages
- **Call-to-Action Links:** Strategic placement of conversion links

## 📊 SEO Implementation Plan

### Technical SEO
- [ ] Clean URL structure
- [ ] Proper heading hierarchy (H1-H6)
- [ ] Meta descriptions for all pages
- [ ] Alt text for all images
- [ ] Fast loading times (<3 seconds)
- [ ] Mobile-first responsive design

### Content SEO
- [ ] Keyword research for cannabis industry terms
- [ ] Local SEO optimization for Buffalo/WNY
- [ ] Content calendar for news/blog posts
- [ ] Member-generated content strategy
- [ ] Industry-specific content hubs

### Schema Markup
- [ ] Organization schema
- [ ] Event schema for all events
- [ ] Article schema for news posts
- [ ] Person schema for members
- [ ] LocalBusiness schema
- [ ] FAQ schema for FAQ pages

## 🎨 Design System Integration

### CSS Architecture
```
style.css (main theme file)
├── design-system.css (CSS variables & utilities)
├── components.css (reusable components)
├── layouts.css (page layouts)
└── print.css (print styles)
```

### Component Library
- [ ] Button variants (primary, secondary, outline)
- [ ] Card components (news, events, members)
- [ ] Form elements (inputs, selects, textareas)
- [ ] Navigation components (header, footer, breadcrumbs)
- [ ] Hero sections (with/without image, with/without form)
- [ ] CTA sections (various styles)
- [ ] Accordion/toggle components
- [ ] Modal/dialog components

## 🚀 Performance Targets

### Core Web Vitals
- **LCP (Largest Contentful Paint):** < 2.5s
- **FID (First Input Delay):** < 100ms
- **CLS (Cumulative Layout Shift):** < 0.1

### Additional Metrics
- **Page Load Time:** < 3s
- **Time to Interactive:** < 4s
- **First Contentful Paint:** < 1.5s
- **Speed Index:** < 3s

## 📝 Next Steps

1. **Immediate (Today):**
   - Complete missing templates
   - Set up custom post types
   - Integrate design system

2. **This Week:**
   - Implement SEO foundation
   - Create component library
   - Set up internal linking

3. **Next Week:**
   - Performance optimization
   - Member portal features
   - Content management setup

4. **Final Week:**
   - Testing & QA
   - Launch preparation
   - Documentation

## 🔍 Success Metrics

### SEO Metrics
- Organic traffic growth
- Keyword rankings
- Click-through rates
- Bounce rate reduction

### User Engagement
- Time on site
- Pages per session
- Event registrations
- Membership signups

### Technical Performance
- Page load speeds
- Mobile usability scores
- Accessibility compliance
- Search console health

---

**Note:** This plan should be reviewed and updated weekly based on progress and feedback.
