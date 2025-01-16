<?php
class layout
{
    protected $title = 'Las Muñecas de Ramón';
    protected $styles = [];
    protected $scripts = [];
    protected $content = '';
    protected $breadcrumbs = [];
    protected $pageIcon = '';
    protected $pageTitle = '';

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setPageTitle($title, $icon = null)
    {
        $this->pageTitle = $title;
        if ($icon) {
            $this->pageIcon = $icon;
        }
        return $this;
    }

    public function addBreadcrumb($label, $url = null)
    {
        $this->breadcrumbs[] = [
            'label' => $label,
            'url' => $url
        ];
        return $this;
    }

    public function addStyles($path)
    {
        $this->styles[] = $path;
        return $this;
    }

    public function addScripts($path)
    {
        $this->scripts[] = $path;
        return $this;
    }

    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public function render()
    {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <?php require_once 'public/views/layout/head.php'; ?>

        <body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed">
            <?php require_once 'public/views/layout/aside.php'; ?>
            
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <div class="container-fluid d-flex align-items-stretch justify-content-between" id="kt_header_container">
                        <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-2 mb-5 mb-lg-0" 
                             data-kt-swapper="true" 
                             data-kt-swapper-mode="prepend" 
                             data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                            
                            <h1 class="text-dark fw-bold mt-1 mb-1 fs-2">
                                <i class="<?php echo htmlspecialchars($this->pageIcon) ?>"></i> 
                                <?php echo htmlspecialchars($this->pageTitle) ?> 
                                <small class="text-muted fs-6 fw-normal ms-1"></small>
                            </h1>
                            
                            <ul class="breadcrumb fw-semibold fs-base mb-1">
                                <?php foreach ($this->breadcrumbs as $breadcrumb): ?>
                                    <li class="breadcrumb-item text-muted">
                                        <?php if ($breadcrumb['url']): ?>
                                            <a href="<?php echo htmlspecialchars($breadcrumb['url']) ?>" class="text-muted text-hover-primary">
                                                <?php echo htmlspecialchars($breadcrumb['label']) ?>
                                            </a>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($breadcrumb['label']) ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <?php require_once 'public/views/layout/navbar.php'; ?>
                    </div>
                </div> 
                <div class="content d-flex flex-column flex-column-fluid fs-6" id="kt_content">
                    <div class="container-fluid">
                        <div class="row gy-5 g-xl-10">
                        <?php
                        if (is_callable($this->content)) {
                            call_user_func($this->content);
                        } else {
                            echo $this->content;
                        }
                        ?>
                        </div>
                        
                    </div>
                </div>

                <?php require_once 'public/views/layout/footer.php'; ?>
            </div>

            <?php foreach ($this->scripts as $script): ?>
                <script src="<?php echo htmlspecialchars($script) ?>"></script>
            <?php endforeach; ?>
        </body>
        </html>
        <?php
    }
}

function layout()
{
    return new layout();
}
