# Content Management System

This system provides a flexible, hierarchical content management structure using Filament admin panel.

## 🏗️ **Architecture Overview**

### **Hierarchy:**

```
Pages → Page Sections → Content Blocks
```

-   **Pages**: Top-level content (About Us, Services, Contact, etc.)
-   **Page Sections**: Organized sections within each page (Mission, Vision, Team, etc.)
-   **Content Blocks**: Individual content pieces (text, images, videos, lists, etc.)

## 📋 **Database Structure**

### **1. Pages Table**

-   `title`, `slug`, `short_description`
-   `is_active`, `display_order`
-   Hero image support via Media Library
-   User tracking (created_by, updated_by)

### **2. Page Sections Table**

-   `page_id` (foreign key to pages)
-   `title`, `subtitle`
-   `is_active`, `display_order`
-   User tracking

### **3. Content Blocks Table**

-   `page_id`, `section_id` (foreign keys)
-   `type`: text, image, video, list, timeline, gallery
-   `title`, `subtitle`, `content`
-   `metadata` (JSON field for flexible data)
-   Media support (images, videos)
-   `is_active`, `display_order`
-   User tracking

## 🚀 **Getting Started**

### **1. Run Migrations**

```bash
php artisan migrate
```

### **2. Seed Sample Data**

```bash
php artisan db:seed --class=PageSeeder
```

### **3. Access Admin Panel**

Navigate to `/admin` and you'll see:

-   **Content Management** → **Pages**
-   **Content Management** → **Page Sections**
-   **Content Management** → **Content Blocks**

## 🎯 **Usage Examples**

### **Creating a New Page:**

1. Go to **Pages** → **Create Page**
2. Fill in title, description, and settings
3. Upload hero image (optional)
4. Save

### **Adding Sections:**

1. Go to **Page Sections** → **Create Page Section**
2. Select the page
3. Add title and subtitle
4. Set display order
5. Save

### **Adding Content Blocks:**

1. Go to **Content Blocks** → **Create Content Block**
2. Select page and section
3. Choose content type
4. Add content and media
5. Save

## 🔧 **Content Block Types**

### **Text Block**

-   Rich text editor
-   Perfect for paragraphs, descriptions

### **Image Block**

-   Single or multiple images
-   Automatic thumbnails
-   Organized storage

### **Video Block**

-   MP4, WebM, OGG support
-   Multiple videos per block

### **List Block**

-   Rich text with list formatting
-   Perfect for features, benefits

### **Timeline Block**

-   Rich text for timeline content
-   Can be enhanced with metadata

### **Gallery Block**

-   Multiple images
-   Perfect for portfolios, showcases

## 📱 **Media Management**

-   **Automatic Organization**: Files are organized by model and collection
-   **Thumbnails**: Automatic generation for images
-   **Multiple Formats**: Support for various image and video formats
-   **Storage Paths**: `/storage/{model}s/{collection}/`

## 🎨 **Frontend Integration**

### **Retrieving Pages:**

```php
// Get active pages
$pages = Page::active()->ordered()->get();

// Get page with sections and content blocks
$page = Page::with(['activeSections.activeContentBlocks'])->find($id);
```

### **Displaying Content:**

```php
@foreach($page->activeSections as $section)
    <h2>{{ $section->title }}</h2>
    @if($section->subtitle)
        <h3>{{ $section->subtitle }}</h3>
    @endif

    @foreach($section->activeContentBlocks as $block)
        @switch($block->type)
            @case('text')
                {!! $block->content !!}
                @break
            @case('image')
                ...
                @break
            @case('video')
                // Logic to display video
                @break
            @case('timeline')
                // Logic to display timeline
                @break
            @case('gallery')
                // Logic to display gallery
                @break
            @case('list')
                ...
                @break
        @endswitch
    @endforeach
@endforeach
```

## 🔄 **Workflow**

1. **Create Page** → Set basic info and hero image
2. **Add Sections** → Organize content into logical sections
3. **Add Content Blocks** → Fill sections with various content types
4. **Order & Activate** → Set display order and activate/deactivate
5. **Preview & Publish** → Content is ready for frontend display

## ✨ **Features**

-   **Soft Deletes**: Safe content removal
-   **User Tracking**: Know who created/updated content
-   **Ordering**: Drag & drop reordering
-   **Status Management**: Active/inactive toggles
-   **Media Library**: Integrated file management
-   **Responsive Design**: Mobile-friendly admin interface
-   **Search & Filters**: Easy content discovery
-   **Bulk Actions**: Efficient content management

## 🚀 **Future Enhancements**

-   **Templates**: Pre-built page layouts
-   **Versioning**: Content revision history
-   **Workflow**: Approval processes
-   **SEO Tools**: Meta tags, sitemaps
-   **Analytics**: Content performance tracking
-   **Multi-language**: Internationalization support

---

**Need Help?** Check the Filament documentation or contact your development team.
