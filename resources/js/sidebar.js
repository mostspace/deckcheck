// Sidebar menu data and functionality
const Sidebar_Items = [
  {
    name: '',
    label: 'Dashboard',
    route: 'v2.dashboard',
    svg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M1.875 5.9375C1.875 5.41973 2.29473 5 2.8125 5H7.8125C8.33027 5 8.75 5.41973 8.75 5.9375V9.0625C8.75 9.58027 8.33027 10 7.8125 10H2.8125C2.29473 10 1.875 9.58027 1.875 9.0625V5.9375Z" stroke="#D3D6EC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M11.875 7.1875C11.875 6.66973 12.2947 6.25 12.8125 6.25H17.1875C17.7053 6.25 18.125 6.66973 18.125 7.1875V14.0625C18.125 14.5803 17.7053 15 17.1875 15H12.8125C12.2947 15 11.875 14.5803 11.875 14.0625V7.1875Z" stroke="#D3D6EC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M3.125 13.4375C3.125 12.9197 3.54473 12.5 4.0625 12.5H8.4375C8.95527 12.5 9.375 12.9197 9.375 13.4375V15.3125C9.375 15.8303 8.95527 16.25 8.4375 16.25H4.0625C3.54473 16.25 3.125 15.8303 3.125 15.3125V13.4375Z" stroke="#D3D6EC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>`,
    activeSvg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.25 5.9375C1.25 5.07456 1.94956 4.375 2.8125 4.375H7.8125C8.67544 4.375 9.375 5.07456 9.375 5.9375V9.0625C9.375 9.92545 8.67544 10.625 7.8125 10.625H2.8125C1.94956 10.625 1.25 9.92544 1.25 9.0625V5.9375ZM11.25 7.1875C11.25 6.32456 11.9496 5.625 12.8125 5.625H17.1875C18.0504 5.625 18.75 6.32456 18.75 7.1875V14.0625C18.75 14.9254 18.0504 15.625 17.1875 15.625H12.8125C11.9496 15.625 11.25 14.9254 11.25 14.0625V7.1875ZM2.5 13.4375C2.5 12.5746 3.19956 11.875 4.0625 11.875H8.4375C9.30044 11.875 10 12.5746 10 13.4375V15.3125C10 16.1754 9.30044 16.875 8.4375 16.875H4.0625C3.19955 16.875 2.5 16.1754 2.5 15.3125V13.4375Z" fill="#16151E"/>
      </svg>`
  },
  {
    name: '',
    label: 'Vessel',
    route: 'vessel.index',
    svg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M16.2703 7.63984L15.25 7.29922V3.375C15.25 3.04348 15.1183 2.72554 14.8839 2.49112C14.6495 2.2567 14.3315 2.125 14 2.125H9.625V0.875C9.625 0.70924 9.55915 0.550268 9.44194 0.433058C9.32473 0.315848 9.16576 0.25 9 0.25C8.83424 0.25 8.67527 0.315848 8.55806 0.433058C8.44085 0.550268 8.375 0.70924 8.375 0.875V2.125H4C3.66848 2.125 3.35054 2.2567 3.11612 2.49112C2.8817 2.72554 2.75 3.04348 2.75 3.375V7.29922L1.72969 7.63984C1.48079 7.72282 1.26431 7.882 1.11091 8.09485C0.957515 8.3077 0.874981 8.56342 0.875 8.82578V10.875C0.875 15.6828 8.52266 17.65 8.84844 17.7312C8.94795 17.7561 9.05205 17.7561 9.15156 17.7312C9.47734 17.65 17.125 15.6828 17.125 10.875V8.82578C17.125 8.56342 17.0425 8.3077 16.8891 8.09485C16.7357 7.882 16.5192 7.72282 16.2703 7.63984ZM4 3.375H14V6.88281L9.19766 5.28203C9.06936 5.23926 8.93064 5.23926 8.80234 5.28203L4 6.88281V3.375ZM15.875 10.875C15.875 12.8211 14.025 14.2344 12.4727 15.0805C11.3674 15.667 10.2034 16.1355 9 16.4781C7.80387 16.139 6.64673 15.6749 5.54766 15.0938C2.71875 13.5586 2.125 11.9523 2.125 10.875V8.82578L8.375 6.74219V12.125C8.375 12.2908 8.44085 12.4497 8.55806 12.5669C8.67527 12.6842 8.83424 12.75 9 12.75C9.16576 12.75 9.32473 12.6842 9.44194 12.5669C9.55915 12.4497 9.625 12.2908 9.625 12.125V6.74219L15.875 8.82578V10.875Z" fill="#D3D6EC"/>
      </svg>`,
    activeSvg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M17.2703 8.63984L16.25 8.29922V4.375C16.25 4.04348 16.1183 3.72554 15.8839 3.49112C15.6495 3.2567 15.3315 3.125 15 3.125H10.625V1.875C10.625 1.70924 10.5592 1.55027 10.4419 1.43306C10.3247 1.31585 10.1658 1.25 10 1.25C9.83424 1.25 9.67527 1.31585 9.55806 1.43306C9.44085 1.55027 9.375 1.70924 9.375 1.875V3.125H5C4.66848 3.125 4.35054 3.2567 4.11612 3.49112C3.8817 3.72554 3.75 4.04348 3.75 4.375V8.29922L2.72969 8.63984C2.48079 8.72282 2.26431 8.882 2.11091 9.09485C1.95752 9.3077 1.87498 9.56342 1.875 9.82578V11.875C1.875 16.6828 9.52266 18.65 9.84844 18.7312C9.94795 18.7561 10.0521 18.7561 10.1516 18.7312C10.4773 18.65 18.125 16.6828 18.125 11.875V9.82578C18.125 9.56342 18.0425 9.3077 17.8891 9.09485C17.7357 8.882 17.5192 8.72282 17.2703 8.63984ZM10.625 13.125C10.625 13.2908 10.5592 13.4497 10.4419 13.5669C10.3247 13.6842 10.1658 13.75 10 13.75C9.83424 13.75 9.67527 13.6842 9.55806 13.5669C9.44085 13.4497 9.375 13.2908 9.375 13.125V8.19297C9.375 8.02721 9.44085 7.86824 9.55806 7.75103C9.67527 7.63382 9.83424 7.56797 10 7.56797C10.1658 7.56797 10.3247 7.63382 10.4419 7.75103C10.5592 7.86824 10.625 8.02721 10.625 8.19297V13.125ZM15 7.88281L10.1977 6.28203C10.0694 6.23926 9.93064 6.23926 9.80234 6.28203L5 7.88281V4.375H15V7.88281Z" fill="#16151E"/>
      </svg>`
  },
  {
    name: '',
    label: 'Maintenance',
    route: 'v2.maintenance.index',
    svg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M8.51613 11.6411L13.375 16.5C14.2379 17.3629 15.6371 17.3629 16.5 16.5C17.3629 15.6371 17.3629 14.2379 16.5 13.375L11.6027 8.47774M8.51613 11.6411L10.5962 9.11529C10.8596 8.79551 11.2118 8.59433 11.6027 8.47774M8.51613 11.6411L4.63693 16.3516C4.23364 16.8413 3.63247 17.125 2.99807 17.125C1.82553 17.125 0.875 16.1745 0.875 15.0019C0.875 14.3675 1.1587 13.7664 1.64842 13.3631L7.34551 8.67134M11.6027 8.47774C12.0606 8.34119 12.5715 8.32067 13.0549 8.36153C13.1604 8.37045 13.2672 8.375 13.375 8.375C15.4461 8.375 17.125 6.69607 17.125 4.625C17.125 4.07478 17.0065 3.55223 16.7936 3.08149L14.0635 5.81164C13.1338 5.59821 12.4019 4.86636 12.1885 3.93664L14.9187 1.20645C14.4479 0.993528 13.9253 0.875 13.375 0.875C11.3039 0.875 9.625 2.55393 9.625 4.625C9.625 4.73282 9.62955 4.83958 9.63847 4.94509C9.71423 5.8413 9.57899 6.83201 8.88471 7.40377L8.79965 7.47382M7.34551 8.67134L3.92417 5.25H2.75L0.875 2.125L2.125 0.875L5.25 2.75V3.92417L8.79965 7.47382M7.34551 8.67134L8.79965 7.47382M14.3125 14.3125L12.125 12.125M3.05603 14.9375H3.06228V14.9438H3.05603V14.9375Z" stroke="#ffffff" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>`,
    activeSvg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 5.625C10 3.20875 11.9588 1.25 14.375 1.25C14.8159 1.25 15.2427 1.31543 15.6456 1.43754C15.8558 1.50126 16.0172 1.67066 16.0706 1.88374C16.124 2.09681 16.0616 2.32228 15.9063 2.47761L13.1407 5.24319C13.1921 5.63951 13.3699 6.02151 13.6742 6.32583C13.9785 6.63014 14.3605 6.80789 14.7568 6.85931L17.5224 4.09373C17.6777 3.9384 17.9032 3.87602 18.1163 3.92942C18.3293 3.98282 18.4987 4.14416 18.5625 4.35438C18.6846 4.75725 18.75 5.18405 18.75 5.625C18.75 8.04125 16.7912 10 14.375 10C14.2496 10 14.1253 9.99471 14.0023 9.98431C13.1546 9.91265 12.4448 10.068 12.0787 10.5126L6.11939 17.7489C5.59737 18.3828 4.81923 18.75 3.99807 18.75C2.48035 18.75 1.25 17.5196 1.25 16.0019C1.25 15.1808 1.61722 14.4026 2.2511 13.8806L9.48739 7.92131C9.93197 7.55519 10.0873 6.84544 10.0157 5.99774C10.0053 5.87473 10 5.75041 10 5.625ZM3.43103 15.9375C3.43103 15.5924 3.71085 15.3125 4.05603 15.3125H4.06228C4.40746 15.3125 4.68728 15.5924 4.68728 15.9375V15.9438C4.68728 16.289 4.40746 16.5688 4.06228 16.5688H4.05603C3.71085 16.5688 3.43103 16.289 3.43103 15.9438V15.9375Z" fill="#16151E"/>
        <path d="M8.39664 7.20026L6.56251 5.36613V4.06251C6.56251 3.84297 6.44733 3.63953 6.25907 3.52658L3.13407 1.65158C2.88815 1.50403 2.57336 1.54278 2.37057 1.74557L1.74557 2.37057C1.54278 2.57336 1.50403 2.88815 1.65158 3.13407L3.52658 6.25907C3.63953 6.44733 3.84297 6.56251 4.06251 6.56251H5.36613L7.08448 8.28086L8.39664 7.20026Z" fill="#16151E"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.4633 14.4406L13.9489 17.9262C15.0472 19.0245 16.828 19.0245 17.9263 17.9262C19.0247 16.8278 19.0247 15.0471 17.9263 13.9487L15.1716 11.194C14.9114 11.2309 14.6455 11.25 14.3751 11.25C14.2145 11.25 14.055 11.2432 13.8971 11.2298C13.5685 11.2021 13.3283 11.2247 13.1741 11.2647C13.0877 11.2871 13.0479 11.3094 13.0349 11.3179L10.4633 14.4406ZM13.3081 13.3081C13.5522 13.064 13.9479 13.064 14.192 13.3081L15.7545 14.8706C15.9986 15.1146 15.9986 15.5104 15.7545 15.7544C15.5104 15.9985 15.1147 15.9985 14.8706 15.7544L13.3081 14.1919C13.0641 13.9479 13.0641 13.5521 13.3081 13.3081Z" fill="#16151E"/>
      </svg>`
  },
  {
    name: '',
    label: 'Inventory',
    route: 'inventory.index',
    svg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
        <path d="M15.875 4.25L15.3538 13.1101C15.2955 14.1012 14.4748 14.875 13.4821 14.875H4.51795C3.52518 14.875 2.70448 14.1012 2.64618 13.1101L2.125 4.25M7.33313 7.375H10.6665M1.8125 4.25H16.1875C16.7053 4.25 17.125 3.83027 17.125 3.3125V2.0625C17.125 1.54473 16.7053 1.125 16.1875 1.125H1.8125C1.29473 1.125 0.875 1.54473 0.875 2.0625V3.3125C0.875 3.83027 1.29473 4.25 1.8125 4.25Z" stroke="#D3D6EC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>`,
    activeSvg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M2.8125 2.5C1.94956 2.5 1.25 3.19956 1.25 4.0625V4.6875C1.25 5.55044 1.94955 6.25 2.8125 6.25H17.1875C18.0504 6.25 18.75 5.55044 18.75 4.6875V4.0625C18.75 3.19956 18.0504 2.5 17.1875 2.5H2.8125Z" fill="#16151E"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.57233 7.5L3.02214 15.1468C3.09987 16.4682 4.19413 17.5 5.51783 17.5H14.4819C15.8056 17.5 16.8999 16.4682 16.9776 15.1468L17.4274 7.5H2.57233ZM7.70813 10.625C7.70813 10.2798 7.98795 10 8.33313 10H11.6665C12.0116 10 12.2915 10.2798 12.2915 10.625C12.2915 10.9702 12.0116 11.25 11.6665 11.25H8.33313C7.98795 11.25 7.70813 10.9702 7.70813 10.625Z" fill="#16151E"/>
      </svg>`
  },
  {
    name: '',
    label: 'Reports',
    route: 'reports.index',
    svg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
        <path d="M0.875 8.625V8C0.875 6.96447 1.71447 6.125 2.75 6.125H15.25C16.2855 6.125 17.125 6.96447 17.125 8V8.625M9.88388 3.25888L8.11612 1.49112C7.8817 1.2567 7.56375 1.125 7.23223 1.125H2.75C1.71447 1.125 0.875 1.96447 0.875 3V13C0.875 14.0355 1.71447 14.875 2.75 14.875H15.25C16.2855 14.875 17.125 14.0355 17.125 13V5.5C17.125 4.46447 16.2855 3.625 15.25 3.625H10.7678C10.4362 3.625 10.1183 3.4933 9.88388 3.25888Z" stroke="#D3D6EC" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>`,
    activeSvg: `
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M16.25 17.5C17.6307 17.5 18.75 16.3807 18.75 15V11.25C18.75 9.86929 17.6307 8.75 16.25 8.75H3.75C2.36929 8.75 1.25 9.86929 1.25 11.25V15C1.25 16.3807 2.36929 17.5 3.75 17.5H16.25Z" fill="#16151E"/>
        <path d="M1.25 8.45487V5C1.25 3.61929 2.36929 2.5 3.75 2.5H8.23223C8.72951 2.5 9.20643 2.69754 9.55806 3.04917L11.3258 4.81694C11.443 4.93415 11.602 5 11.7678 5H16.25C17.6307 5 18.75 6.11929 18.75 7.5V8.45487C18.0866 7.86107 17.2104 7.5 16.25 7.5H3.75C2.78956 7.5 1.91345 7.86107 1.25 8.45487Z" fill="#16151E"/>
      </svg>`
  }
];

// Initialize sidebar menu
function initializeSidebar() {
  const menuEl = document.getElementById('sidebar-menu');
  const titleEl = document.getElementById('page-title');

  if (!menuEl) return;

  // Get current route information from server
  const currentRoute = menuEl.getAttribute('data-current-route');
  const currentPath = menuEl.getAttribute('data-current-path');

  // Build menu
  Sidebar_Items.forEach((item, index) => {
    const li = document.createElement('li');
    let isActive = false;
    
    // Check for active state using server-side route information
    if (item.route === 'v2.dashboard') {
      isActive = currentRoute === 'v2.dashboard' || currentPath === 'v2/dashboard';
    } else if (item.route === 'v2.maintenance.index') {
      // Special handling for v2 maintenance
      isActive = currentRoute === 'v2.maintenance.index' || currentPath === 'v2/maintenance' || currentPath.startsWith('v2/maintenance/');
    } else {
      const routeName = item.route.replace('.index', '');
      isActive = currentRoute === item.route || currentRoute.startsWith(routeName + '.');
    }
    
    const svgToUse = isActive ? (item.activeSvg || item.svg) : item.svg;
    
    li.innerHTML = `${svgToUse}<span>${item.name}</span><span class="tooltip-arrow pointer-events-none absolute left-full px-3 py-1.5 rounded-md bg-primary text-brand-900 text-xs font-normal whitespace-nowrap opacity-0 -translate-x-1 group-hover:opacity-100 group-hover:translate-x-0 transition z-20" style="font-size: 12px;">${item.label}</span>`;
    li.setAttribute('role', 'button');
    li.setAttribute('tabindex', '0');
    li.setAttribute('data-index', index);
    li.setAttribute('data-route', item.route);
    li.setAttribute('data-url', getRouteUrl(item.route));
    li.classList.add('group', 'relative');
    
    if (isActive) {
      li.classList.add('active');
      li.setAttribute('aria-current', 'page');
    }
    
    menuEl.appendChild(li);
  });

  function setActive(index) {
    const lis = Array.from(menuEl.children);
    lis.forEach((li, i) => {
      li.classList.remove('active');
      li.removeAttribute('aria-current');
      const item = Sidebar_Items[i];
      const svgElement = li.querySelector('svg');
      if (svgElement && item) {
        const svgToUse = i === index ? (item.activeSvg || item.svg) : item.svg;
        svgElement.outerHTML = svgToUse;
      }
    });
    const active = lis[index];
    if (active) {
      active.classList.add('active');
      active.setAttribute('aria-current', 'page');
      if (titleEl) {
        titleEl.textContent = Sidebar_Items[index].name;
      }
    }
  }

  // Click / keyboard handlers
  menuEl.addEventListener('click', (e) => {
    const li = e.target.closest('li');
    if (!li) return;
    const index = parseInt(li.getAttribute('data-index'), 10);
    const url = li.getAttribute('data-url');
    if (url) {
      window.location.href = url;
    } else {
      setActive(index);
    }
  });

  menuEl.addEventListener('keydown', (e) => {
    const lis = Array.from(menuEl.children);
    const current = lis.findIndex(li => li.classList.contains('active'));
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      setActive(Math.min(current + 1, lis.length - 1));
      lis[Math.min(current + 1, lis.length - 1)].focus();
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      setActive(Math.max(current - 1, 0));
      lis[Math.max(current - 1, 0)].focus();
    } else if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      const focusIndex = lis.indexOf(document.activeElement);
      if (focusIndex >= 0) {
        const url = lis[focusIndex].getAttribute('data-url');
        if (url) {
          window.location.href = url;
        } else {
          setActive(focusIndex);
        }
      }
    }
  });
}

// Get route URL helper
function getRouteUrl(route) {
  const routes = {
    'v2.dashboard': '/v2/dashboard',
    'vessel.index': '/vessel',
    'v2.maintenance.index': '/v2/maintenance',
    'inventory.index': '/inventory',
    'reports.index': '/reports'
  };
  return routes[route] || '#';
}

// Profile button functionality
function initializeProfileButton() {
  const profileBtn = document.getElementById('btnOpenProfile');
  if (profileBtn) {
    profileBtn.addEventListener('click', function(e) {
      e.preventDefault();
      // Open user modal or profile page
      const userModal = document.getElementById('user-modal');
      if (userModal) {
        userModal.classList.remove('hidden');
      } else {
        // Fallback to profile page
        window.location.href = '/profile';
      }
    });
  }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  initializeSidebar();
  initializeProfileButton();
});