<div class="main-menu menu-fixed menu-light menu-accordion  menu-shadow " data-scroll-to-active="true" >
    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <li class="nav-item">
                <a href="{{ route('dashboard.show_translate', 'en') }}">
                    <i class="fa fa-comments"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">ترجمة النصوص الانجليزية</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.show_translate', 'ar') }}">
                    <i class="fa fa-comments"></i>
                    <span class="menu-title" data-i18n="nav.templates.main">ترجمة النصوص العربية</span>
                </a>
            </li>

        </ul>

    </div>
</div>
