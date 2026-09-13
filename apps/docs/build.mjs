// ─────────────────────────────────────────────────────────────────────────────
// Aegis UI docs — page generator.
//
// Builds every route (index.html, button.html, …) from:
//   - a shared layout (head, mobile/desktop sidebar, header) defined below
//   - page content in src/pages/<name>.html
//
// The sidebar/nav lives here — edit it once, every page updates.
// Usage: node build.mjs   (wired into `npm run build` via prebuild)
// ─────────────────────────────────────────────────────────────────────────────
import { readFileSync, writeFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';

const pagesDir = fileURLToPath(new URL('./src/pages/', import.meta.url));
const outDir = fileURLToPath(new URL('./', import.meta.url));

// ── Nav (single source of truth) ─────────────────────────────────────────────
const NAV = [
  {
    section: 'Getting Started',
    items: [
      { key: 'index', label: 'Home', icon: 'ti-home', href: 'index.html' },
      { key: 'about', label: 'About', icon: 'ti-info-circle', href: 'about.html' },
      { key: 'installation', label: 'Installation', icon: 'ti-terminal', href: 'installation.html' },
      { key: 'theming', label: 'Theming & Customization', icon: 'ti-palette', href: 'theming.html' },
    ],
  },
  {
    section: 'Components',
    items: [
      { key: 'components', label: 'Overview', icon: 'ti-components', href: 'components.html' },
      { key: 'accordion', label: 'Accordion', icon: 'ti-layout-list', href: 'accordion.html' },
      { key: 'alert', label: 'Alert', icon: 'ti-bell', href: 'alert.html' },
      { key: 'banner', label: 'Banner', icon: 'ti-speakerphone', href: 'banner.html' },
      { key: 'button', label: 'Button', icon: 'ti-click', href: 'button.html' },
      { key: 'card', label: 'Card', icon: 'ti-id', href: 'card.html' },
      { key: 'checkbox', label: 'Checkbox', icon: 'ti-square-check', href: 'checkbox.html' },
      { key: 'datepicker', label: 'Date Picker', icon: 'ti-calendar', href: 'datepicker.html' },
      { key: 'dropdown', label: 'Dropdown', icon: 'ti-menu-2', href: 'dropdown.html' },
      { key: 'form-field', label: 'Form Field', icon: 'ti-list-check', href: 'form-field.html' },
      { key: 'icon', label: 'Icon', icon: 'ti-icons', href: 'icon.html' },
      { key: 'input', label: 'Input', icon: 'ti-forms', href: 'input.html' },
      { key: 'spinner', label: 'Spinner', icon: 'ti-loader-2', href: 'spinner.html' },
      { key: 'textarea', label: 'Textarea', icon: 'ti-text-wrap', href: 'textarea.html' },
    ],
  },
];

// src page file -> output route + metadata. `toc: true` adds an in-page
// "On this page" anchor menu (the only place anchor links are used).
const PAGES = [
  { src: 'home', out: 'index', navKey: 'index', toc: false,
    title: 'Aegis UI — Documentation',
    description: 'A token-driven Blade + Livewire component library for Laravel 13, Livewire 4, Alpine 3 and Tailwind CSS 4.' },
  { src: 'about', out: 'about', navKey: 'about', toc: false,
    title: 'About — Aegis UI Docs',
    description: 'The story, the stack and the design principles behind Aegis UI.' },
  { src: 'installation', out: 'installation', navKey: 'installation', toc: false,
    title: 'Installation — Aegis UI Docs',
    description: 'Add Aegis UI to any Laravel 13 project: install, publish config and assets, import the stylesheet.' },
  { src: 'theming', out: 'theming', navKey: 'theming', toc: false,
    title: 'Theming & Customization — Aegis UI Docs',
    description: 'Every visual value is a --ui-* token. Change the tokens, rebrand the library.' },
  { src: 'components', out: 'components', navKey: 'components', toc: false,
    title: 'Components — Aegis UI Docs',
    description: 'Every component page documents each prop and customization option.' },
  { src: 'banner', out: 'banner', navKey: 'banner', toc: true,
    title: 'Banner — Aegis UI Docs',
    description: 'Banner component: announcement bar with transitions, dismiss, auto-hide and action slots.' },
  { src: 'button', out: 'button', navKey: 'button', toc: true,
    title: 'Button — Aegis UI Docs',
    description: 'Button component: variants, sizes, colors, icons, links, loading and block.' },
  { src: 'card', out: 'card', navKey: 'card', toc: true,
    title: 'Card — Aegis UI Docs',
    description: 'Card component: flexible content container with media, header, body, footer and avatar slots.' },
  { src: 'input', out: 'input', navKey: 'input', toc: true,
    title: 'Input — Aegis UI Docs',
    description: 'Input component: variants, sizes, leading/trailing icons, validation and loading.' },
  { src: 'textarea', out: 'textarea', navKey: 'textarea', toc: true,
    title: 'Textarea — Aegis UI Docs',
    description: 'Textarea component: variants, sizes, auto-resize, character count and validation.' },
  { src: 'checkbox', out: 'checkbox', navKey: 'checkbox', toc: true,
    title: 'Checkbox — Aegis UI Docs',
    description: 'Checkbox component: radius levels, sizes, colors, indeterminate and block/card mode.' },
  { src: 'datepicker', out: 'datepicker', navKey: 'datepicker', toc: true,
    title: 'Date Picker — Aegis UI Docs',
    description: 'Date Picker component: 7 modes, formats, presets, week numbers, disabled dates and ranges.' },
  { src: 'alert', out: 'alert', navKey: 'alert', toc: true,
    title: 'Alert — Aegis UI Docs',
    description: 'Alert component: variants, colors, auto icons and dismissible.' },
  { src: 'accordion', out: 'accordion', navKey: 'accordion', toc: true,
    title: 'Accordion — Aegis UI Docs',
    description: 'Accordion component: single or multi-open, variants and leading icons.' },
  { src: 'dropdown', out: 'dropdown', navKey: 'dropdown', toc: true,
    title: 'Dropdown — Aegis UI Docs',
    description: 'Dropdown component: contextual menus with items, headers, dividers, checkboxes and submenus.' },
  { src: 'spinner', out: 'spinner', navKey: 'spinner', toc: true,
    title: 'Spinner — Aegis UI Docs',
    description: 'Spinner component: sizes, colors and loading states.' },
  { src: 'icon', out: 'icon', navKey: 'icon', toc: true,
    title: 'Icon — Aegis UI Docs',
    description: 'Icon component: any Tabler icon, sizes, color and aria labels.' },
  { src: 'form-field', out: 'form-field', navKey: 'form-field', toc: true,
    title: 'Form Field — Aegis UI Docs',
    description: 'Form Field wrapper: label, required marker, hint, error and valid feedback.' },
];

// ── Nav rendering ────────────────────────────────────────────────────────────
function renderNav(activeKey) {
  const item = (it) => {
    const active = it.key === activeKey;
    const cls = active
      ? 'mb-1 flex items-center gap-2 rounded-md px-2 py-1.5 text-sm transition-colors bg-muted text-foreground font-medium'
      : 'mb-1 flex items-center gap-2 rounded-md px-2 py-1.5 text-sm transition-colors text-muted-foreground hover:text-foreground';
    const aria = active ? ' aria-current="page"' : '';
    return `<a href="${it.href}"${aria} class="${cls}"><i class="ti ${it.icon} text-base"></i> ${it.label}</a>`;
  };

  return NAV.map((group, gi) => {
    const pad = gi === 0 ? '' : ' pt-6';
    return `<p class="px-2 pb-2 text-xs font-semibold uppercase tracking-wider text-muted-foreground${pad}">${group.section}</p>
            ${group.items.map(item).join('\n            ')}`;
  }).join('\n\n            ');
}

// ── On-page TOC (in-page anchors only) ──────────────────────────────────────
function slugify(text) {
  return text
    .toLowerCase()
    .replace(/&amp;/g, 'and')
    .replace(/&/g, 'and')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function withOnPageToc(content) {
  const heads = [];
  const re = /<h2([^>]*)>([\s\S]*?)<\/h2>/g;
  let m;
  while ((m = re.exec(content)) !== null) {
    const label = m[2].replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
    heads.push({ id: slugify(label), label });
  }
  if (!heads.length) return content;

  let i = 0;
  content = content.replace(/<h2([^>]*)>([\s\S]*?)<\/h2>/g, (_f, attrs, inner) => {
    const label = inner.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
    const id = heads[i++].id;
    const attrsWithClass = attrs.includes('class=')
      ? attrs.replace(/class="([^"]*)"/, 'class="$1 scroll-mt-20"')
      : `${attrs} class="scroll-mt-20"`;
    return `<h2${attrsWithClass} id="${id}">${inner}</h2>`;
  });

  const toc = `
          <div class="docs-card mt-8" aria-label="On this page">
            <span class="docs-card__label">On this page</span>
            <nav class="flex flex-wrap gap-2">
              ${heads.map((h) => `<a href="#${h.id}" class="rounded-md border border-border px-2.5 py-1 text-xs font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground">${h.label}</a>`).join('\n              ')}
            </nav>
          </div>`;

  return content.replace(
    /(<p class="mt-2 text-muted-foreground">[\s\S]*?<\/p>)/,
    `$1\n${toc}`
  );
}

// ── Shared shell ────────────────────────────────────────────────────────────
function shell({ title, description, activeKey, content, pageScript }) {
  const nav = renderNav(activeKey);
  const brand = (mobile) => `
          ${mobile ? '<div class="flex h-14 items-center gap-2 border-b border-border px-5">' : '<a href="index.html" class="flex h-14 items-center gap-2 border-b border-border px-5">'}
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-primary text-primary-foreground">
              <i class="ti ti-shield text-sm"></i>
            </span>
            <span class="text-sm font-bold tracking-tight">Aegis UI</span>
          ${mobile ? '</div>' : '</a>'}`;

  return `<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>${title}</title>
    <meta name="description" content="${description}" />

    <!-- Alpine.js Collapse Plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tabler Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <!-- Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/src/styles.css" />
  </head>

  <body x-data="{ sidebarOpen: false }" class="bg-background text-foreground antialiased">
    <!-- ───────────────────────── Mobile sidebar overlay ───────────────────────── -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
      <div class="absolute inset-0 bg-black/40" @click="sidebarOpen = false"></div>
      <aside class="absolute inset-y-0 left-0 flex w-72 flex-col overflow-y-auto border-r border-border bg-background">
        ${brand(true)}
        <nav class="flex-1 overflow-y-auto px-4 py-6">
          ${nav}
        </nav>
      </aside>
    </div>

    <div class="lg:pl-64">
      <!-- ───────────────────────── Desktop sidebar ───────────────────────── -->
      <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-border bg-background lg:flex">
        ${brand(false)}
        <nav class="flex-1 overflow-y-auto px-4 py-6">
          ${nav}
        </nav>
      </aside>

      <!-- ───────────────────────── Top header ───────────────────────── -->
      <header class="sticky top-0 z-30 flex h-14 items-center justify-between border-b border-border bg-background/80 px-4 backdrop-blur sm:px-6">
        <div class="flex items-center gap-3">
          <button
            type="button"
            @click="sidebarOpen = true"
            class="flex h-9 w-9 items-center justify-center rounded-md border border-border text-muted-foreground transition-colors hover:text-foreground lg:hidden"
            aria-label="Open navigation"
          >
            <i class="ti ti-menu-2 text-lg"></i>
          </button>
          <span class="text-sm font-medium text-muted-foreground lg:hidden">Aegis UI</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="version-chip"><i class="ti ti-shield text-xs text-foreground"></i> v1.0.0</span>
          <span class="hidden sm:inline-flex text-xs text-muted-foreground">Laravel 13 · Livewire 4 · Tailwind 4 · Alpine 3</span>
        </div>
      </header>

      <!-- ───────────────────────── Main content ───────────────────────── -->
      <main class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6">
        ${content}
      </main>
    </div>

    ${pageScript}
  </body>
</html>
`;
}

// ── Assemble every page ─────────────────────────────────────────────────────
for (const page of PAGES) {
  const raw = readFileSync(`${pagesDir}${page.src}.html`, 'utf8');
  const scriptIdx = raw.indexOf('<script>');
  const contentRaw = scriptIdx === -1 ? raw : raw.slice(0, scriptIdx);
  const pageScript = scriptIdx === -1 ? '' : raw.slice(scriptIdx);

  const content = page.toc ? withOnPageToc(contentRaw) : contentRaw;

  const html = shell({ ...page, activeKey: page.navKey, content, pageScript });
  writeFileSync(`${outDir}${page.out}.html`, html);
  console.log(`${page.out}.html  ${html.length} bytes`);
}
