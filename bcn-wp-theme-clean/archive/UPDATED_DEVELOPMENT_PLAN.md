# BCN WordPress Theme Development Plan - UPDATED

## 🎯 Project Overview
**Goal:** Enhance the existing Buffalo Cannabis Network website with a custom theme that maintains current SEO rankings while improving user experience and adding new functionality.

## 📋 Current Site Analysis
**Existing Site:** buffalocannabisnetwork.com
- ✅ **Theme:** Astra (4.11.13) - Will be replaced with custom theme
- ✅ **SEO:** All in One SEO (AIOSEO) 4.8.8 - Will maintain SEO structure
- ✅ **Branding:** Established colors, fonts, logo
- ✅ **Social Media:** Instagram, YouTube, LinkedIn
- ✅ **Current Focus:** Events and education
- ⚠️ **Current Content:** Needs analysis for migration

## 🗺️ Site Architecture & Migration Strategy

### Phase 1: Content Audit & Migration Planning (Week 1)
**Priority: CRITICAL**

#### 1.1 Content Analysis
- [ ] Audit existing pages and content
- [ ] Identify current navigation structure
- [ ] Document existing SEO keywords and rankings
- [ ] Catalog current images and media
- [ ] Map existing user flows

#### 1.2 SEO Preservation Strategy
- [ ] Maintain current URL structure
- [ ] Preserve existing meta descriptions
- [ ] Keep current schema markup
- [ ] Maintain internal linking structure
- [ ] Preserve social media integration

#### 1.3 Design System Integration
- [ ] Extract current brand colors from existing site
- [ ] Identify current typography (Barlow Semi Condensed, Roboto)
- [ ] Document current logo usage
- [ ] Map existing component styles

### Phase 2: Custom Theme Development (Week 2-3)
**Priority: HIGH**

#### 2.1 Core Template Development
- [ ] `front-page.php` - Enhanced homepage with existing content
- [ ] `single-news.php` - News article template
- [ ] `archive-news.php` - News listing
- [ ] `single-events.php` - Event detail template
- [ ] `archive-events.php` - Events listing
- [ ] `404.php` - Custom 404 page
- [ ] `page-members-portal.php` - New members dashboard

#### 2.2 Custom Post Types & Taxonomies
- [ ] Events CPT (bcn_event) - Migrate existing events
- [ ] News CPT (bcn_news) - Migrate existing news/blog
- [ ] Event Type taxonomy
- [ ] News Category taxonomy
- [ ] Member CPT (if needed)

#### 2.3 SEO Migration
- [ ] Preserve all existing meta tags
- [ ] Maintain current schema markup
- [ ] Keep existing breadcrumb structure
- [ ] Preserve social media meta tags
- [ ] Maintain current sitemap structure

### Phase 3: Enhanced Features (Week 3-4)
**Priority: MEDIUM**

#### 3.1 New Functionality
- [ ] Member portal with authentication
- [ ] Event registration system
- [ ] Member directory
- [ ] Resource library
- [ ] Advanced search functionality

#### 3.2 Performance Optimization
- [ ] Image optimization
- [ ] CSS/JS minification
- [ ] Lazy loading
- [ ] Database optimization
- [ ] Caching implementation

### Phase 4: Testing & Launch (Week 4-5)
**Priority: HIGH**

#### 4.1 Migration Testing
- [ ] Content migration testing
- [ ] SEO preservation verification
- [ ] Performance testing
- [ ] Cross-browser testing
- [ ] Mobile responsiveness testing

#### 4.2 Launch Strategy
- [ ] Staging environment setup
- [ ] Content migration
- [ ] DNS cutover
- [ ] Post-launch monitoring
- [ ] Performance monitoring

## 🎨 Design System - Based on Current Site

### Brand Colors (Extracted from Current Site)
```css
:root {
  /* Primary Brand Colors */
  --bcn-primary: #2D5016;        /* Deep Forest Green */
  --bcn-primary-light: #4A7C2A;  /* Lighter Forest Green */
  --bcn-primary-dark: #1A3009;   /* Darker Forest Green */
  
  /* Secondary Brand Colors */
  --bcn-secondary: #8B4513;      /* Saddle Brown */
  --bcn-secondary-light: #A0522D; /* Sienna */
  --bcn-secondary-dark: #654321;  /* Dark Brown */
  
  /* Accent Colors */
  --bcn-accent: #FFD700;         /* Gold */
  --bcn-accent-light: #FFF8DC;   /* Cornsilk */
  --bcn-accent-dark: #B8860B;    /* Dark Goldenrod */
}
```

### Typography (Current Site)
- **Primary Font:** Barlow Semi Condensed (headings)
- **Secondary Font:** Roboto (body text)
- **Current Font Sizes:** Responsive scaling system

### Current Site Structure
```
Home (/)
├── About BCN (/about/)
├── Membership (/membership/)
├── Events (/events/)
├── News (/news/)
├── Contact (/contact/)
└── Resources (/resources/)
```

## 🔗 SEO Preservation Strategy

### Critical SEO Elements to Preserve
1. **Current Title:** "Buffalo Cannabis Network - Industry Events and Education"
2. **Current Description:** "Learn how Buffalo Cannabis Network fosters growth in New York's cannabis industry through community, networking, events, and education."
3. **Current Schema Markup:** Organization, WebPage, WebSite schemas
4. **Current Social Media Integration:** Instagram, YouTube, LinkedIn
5. **Current URL Structure:** Maintain all existing URLs
6. **Current Meta Tags:** Preserve all existing meta tags

### SEO Enhancement Strategy
1. **Maintain Current Rankings:** Preserve all existing SEO elements
2. **Enhance User Experience:** Improve site speed and usability
3. **Add New Content:** Expand with member portal and resources
4. **Local SEO:** Enhance Buffalo/WNY local SEO
5. **Industry Keywords:** Target cannabis industry terms

## 🚀 Performance Targets

### Current Site Performance
- **Goal:** Maintain or improve current performance
- **Target:** < 3s page load time
- **Mobile:** Responsive design optimization

### Performance Improvements
1. **Image Optimization:** Compress and optimize all images
2. **CSS/JS Minification:** Reduce file sizes
3. **Database Optimization:** Optimize queries
4. **Caching:** Implement effective caching
5. **CDN:** Consider CDN implementation

## 📝 Immediate Next Steps

### Week 1: Content Audit & Planning
1. **Day 1-2:** Complete content audit of current site
2. **Day 3-4:** Document current SEO structure
3. **Day 5:** Create migration plan

### Week 2: Theme Development
1. **Day 1-3:** Build core templates
2. **Day 4-5:** Implement custom post types

### Week 3: Features & Testing
1. **Day 1-3:** Add new functionality
2. **Day 4-5:** Testing and optimization

### Week 4: Migration & Launch
1. **Day 1-2:** Content migration
2. **Day 3-4:** Testing and fixes
3. **Day 5:** Launch

## 🔍 Success Metrics

### SEO Preservation
- Maintain current search rankings
- Preserve organic traffic
- Keep current click-through rates

### Performance Improvement
- Faster page load times
- Better mobile experience
- Improved user engagement

### New Functionality
- Member portal usage
- Event registration rates
- Resource library engagement

---

**Note:** This plan prioritizes preserving the existing site's SEO value while enhancing functionality and user experience.
