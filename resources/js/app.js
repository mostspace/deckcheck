import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Main JavaScript functionality for DeckCheck application

// Mobile sidebar functionality
const mobileSidebar = document.getElementById('mobileSidebar');
const panel = document.getElementById('panel');
const backdrop = document.getElementById('backdrop');
const openBtn = document.getElementById('btnOpenSidebar');
const closeBtn = document.getElementById('btnCloseSidebar');

function openSidebar() {
  if (mobileSidebar) {
    mobileSidebar.classList.remove('hidden');
    requestAnimationFrame(() => {
      if (backdrop) backdrop.classList.remove('opacity-0');
      if (panel) {
        panel.classList.remove('-translate-x-full','translate-x-[-100%]');
        panel.classList.add('translate-x-0');
      }
    });
  }
}

function closeSidebar() {
  if (backdrop) backdrop.classList.add('opacity-0');
  if (panel) {
    panel.classList.remove('translate-x-0');
    panel.classList.add('translate-x-[-100%]');
  }
  setTimeout(() => {
    if (mobileSidebar) mobileSidebar.classList.add('hidden');
  }, 200);
}

// Event listeners for mobile sidebar
openBtn?.addEventListener('click', openSidebar);
closeBtn?.addEventListener('click', closeSidebar);
backdrop?.addEventListener('click', closeSidebar);
window.addEventListener('keydown', (e) => { 
  if (e.key === 'Escape') closeSidebar(); 
});

// Announcement dismissal
document.getElementById('btnDismissAnnouncement')?.addEventListener('click', () => {
  const el = document.getElementById('announcement');
  if (!el) return;
  
  el.style.transition = 'opacity 0.3s ease-out, height 0.3s ease-out, padding 0.3s ease-out, margin 0.3s ease-out';
  const startHeight = el.offsetHeight;
  el.style.height = startHeight + 'px';
  void el.offsetHeight;
  
  el.classList.add('opacity-0');
  el.style.height = '0px';
  el.style.paddingTop = '0px';
  el.style.paddingBottom = '0px';
  el.style.marginBottom = '0px';
  
  setTimeout(() => el.remove(), 300);
});

// Timeframe dropdown functionality
const timeframeDropdown = document.getElementById('timeframeDropdown');
const timeframeMenu = document.getElementById('timeframeMenu');
const selectedTimeframe = document.getElementById('selectedTimeframe');
const chevronIcon = timeframeDropdown?.querySelector('img');

function toggleDropdown() {
  if (!timeframeMenu) return;
  const isOpen = timeframeMenu.classList.contains('opacity-100');
  
  if (isOpen) {
    timeframeMenu.classList.remove('opacity-100', 'visible', 'scale-100');
    timeframeMenu.classList.add('opacity-0', 'invisible', 'scale-95');
    chevronIcon?.classList.remove('rotate-180');
  } else {
    timeframeMenu.classList.remove('opacity-0', 'invisible', 'scale-95');
    timeframeMenu.classList.add('opacity-100', 'visible', 'scale-100');
    chevronIcon?.classList.add('rotate-180');
  }
}

function closeDropdown() {
  if (!timeframeMenu) return;
  timeframeMenu.classList.remove('opacity-100', 'visible', 'scale-100');
  timeframeMenu.classList.add('opacity-0', 'invisible', 'scale-95');
  chevronIcon?.classList.remove('rotate-180');
}

timeframeDropdown?.addEventListener('click', (e) => {
  e.stopPropagation();
  toggleDropdown();
});

timeframeMenu?.addEventListener('click', (e) => {
  const button = e.target.closest('button[data-value]');
  if (button) {
    const value = button.dataset.value;
    const text = button.textContent.trim();
    
    if (selectedTimeframe) selectedTimeframe.textContent = text;
    closeDropdown();
    console.log('Selected timeframe:', value, text);
  }
});

document.addEventListener('click', (e) => {
  if (!timeframeDropdown?.contains(e.target)) {
    closeDropdown();
  }
});

// Right drawer functionality
const infoDrawer = document.getElementById('infoDrawer');
const closeDrawer = document.getElementById('btnCloseDrawer');
const helpButtons = Array.from(document.querySelectorAll('[aria-label="Help"], [title="Help"]'));

function openDrawer() {
  infoDrawer?.classList.remove('hidden');
  requestAnimationFrame(() => infoDrawer?.classList.remove('translate-x-full'));
  document.body.classList.add('overflow-hidden');
}

function closeDrawerFn() {
  infoDrawer?.classList.add('translate-x-full');
  setTimeout(() => infoDrawer?.classList.add('hidden'), 200);
  document.body.classList.remove('overflow-hidden');
}

helpButtons.forEach(btn => btn?.addEventListener('click', openDrawer));
closeDrawer?.addEventListener('click', closeDrawerFn);
window.addEventListener('keydown', (e) => { 
  if (e.key === 'Escape') closeDrawerFn(); 
});

// Tab functionality
const tabList = document.querySelector('[role="tablist"]');
const tabs = Array.from(tabList?.querySelectorAll('[role="tab"]') || []);
const panels = tabs.map(t => document.getElementById(t.getAttribute('aria-controls')));

function setActiveTab(nextTab) {
  tabs.forEach((tab, i) => {
    const panel = panels[i];
    const isActive = tab === nextTab;
    tab.setAttribute('aria-selected', String(isActive));
    tab.tabIndex = isActive ? 0 : -1;
    
    if (isActive) {
      // Check if this is the workflow tab
      const isWorkflowTab = tab.id === 'tab-workflow';
      if (isWorkflowTab) {
        // Workflow tab should always have primary/50 background, even when active
        tab.className = 'px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0';
      } else {
        // Regular tab active state - white background
        tab.className = 'px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-white text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0';
      }
      const icon = tab.querySelector('img');
      if (icon) {
        icon.classList.remove('text-slate-500');
        icon.classList.add('text-slate-900');
      }
      panel?.classList.remove('hidden');
      panel?.classList.add('block');
    } else {
      // Check if this is the workflow tab
      const isWorkflowTab = tab.id === 'tab-workflow';
      if (isWorkflowTab) {
        // Workflow tab always has primary/50 background
        tab.className = 'px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 rounded-t-md flex-shrink-0';
      } else {
        // Regular tabs have default styling
        tab.className = 'px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border hover:bg-white rounded-t-md flex-shrink-0';
      }
      const icon = tab.querySelector('img');
      if (icon) {
        if (isWorkflowTab) {
          icon.classList.remove('text-slate-500');
          icon.classList.add('text-slate-900');
        } else {
          icon.classList.remove('text-slate-900');
          icon.classList.add('text-slate-500');
        }
      }
      panel?.classList.add('hidden');
      panel?.classList.remove('block');
    }
  });
  nextTab.focus();
}

function getCurrentIndex() {
  return Math.max(0, tabs.findIndex(t => t.getAttribute('aria-selected') === 'true'));
}

tabs.forEach(tab => {
  tab.addEventListener('click', () => setActiveTab(tab));
  tab.addEventListener('keydown', (e) => {
    const key = e.key;
    let idx = getCurrentIndex();
    if (key === 'ArrowRight') { 
      e.preventDefault(); 
      setActiveTab(tabs[(idx + 1) % tabs.length]); 
    }
    else if (key === 'ArrowLeft') { 
      e.preventDefault(); 
      setActiveTab(tabs[(idx - 1 + tabs.length) % tabs.length]); 
    }
    else if (key === 'Home') { 
      e.preventDefault(); 
      setActiveTab(tabs[0]); 
    }
    else if (key === 'End') { 
      e.preventDefault(); 
      setActiveTab(tabs[tabs.length - 1]); 
    }
    else if (key === 'Enter' || key === ' ') { 
      e.preventDefault(); 
      setActiveTab(tab); 
    }
  });
});

// Sidebar functionality
const sidebarButtons = Array.from(document.querySelectorAll('aside nav button'));
const sidebarNav = document.querySelector('aside nav');

function makeActiveSidebarButton(btn) {
  const activeClasses = ['bg-accent-500','text-brand-900','shadow-soft','ring-2','ring-white','overflow-visible','relative','z-10'];
  sidebarButtons.forEach(b => {
    b.classList.remove(...activeClasses);
    b.removeAttribute('aria-current');
    const icon = b.querySelector('img');
    if (icon) {
      icon.classList.remove('filter', 'invert');
    }
  });
  btn.classList.add(...activeClasses);
  btn.setAttribute('aria-current','page');
  const icon = btn.querySelector('img');
  if (icon) {
    icon.classList.add('filter', 'invert');
  }
}

const preActive = document.querySelector('aside nav button.bg-accent-500');
if (preActive) {
  makeActiveSidebarButton(preActive);
}
sidebarButtons.forEach(b => b.addEventListener('click', () => makeActiveSidebarButton(b)));

// Real-time clock functionality
function updateTime() {
  const now = new Date();
  const pdtTime = new Date(now.toLocaleString("en-US", {timeZone: "America/Los_Angeles"}));
  
  const timeString = pdtTime.toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  });
  
  const timeElement = document.getElementById('currentTime');
  if (timeElement) {
    timeElement.textContent = `${timeString} (PDT)`;
  }
}

// Initialize functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  updateTime();
  setInterval(updateTime, 60000);
});
