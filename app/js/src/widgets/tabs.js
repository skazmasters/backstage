function triggerEvent(el, eventName, options) {
  let event;

  if (window.CustomEvent) {
    event = new CustomEvent(eventName, options);
  } else {
    event = document.createEvent('CustomEvent');
    event.initCustomEvent(eventName, true, true, options);
  }

  el.dispatchEvent(event);
}


class Tabs {
  constructor(nodeElement) {
    this.nodeElement = nodeElement;
    this.nodeElement.classList.add('js-tabs');

    this.isNoClass = 'tabsNoClass' in nodeElement.dataset;

    this.tabs = [];

    nodeElement.querySelectorAll('.js-tab').forEach(item => {
      if (item.closest('.js-tabs') === this.nodeElement) {
        this.tabs.push(item);
      }
    });

    if (this.tabs.length === 0) {
      return;
    }

    const selectors = [];
    this.tabs.forEach(tab => selectors.push(tab.dataset.target));

    this.mobileTabs = nodeElement.querySelectorAll('.js-tab-mobile');

    this.tabContents = [];
    nodeElement.querySelectorAll(selectors.join(',')).forEach(item => {
      if (item.closest('.js-tabs') === this.nodeElement) {
        this.tabContents.push(item);
      }
    });

    this.init();
    this.setDefaults();
  }

  init() {
    this.tabs.forEach(tab => tab.addEventListener('click', e => {
      e.preventDefault();
      this.onTabClick(tab)
    }));

    this.mobileTabs.forEach(tab => tab.addEventListener('click', e => {
      e.preventDefault();
      this.onTabClick(tab);
    }));
  }

  setDefaults() {
    let activeTab = null;
    this.tabs.forEach(item => {
      if (item.classList.contains('active')) {
        activeTab = item;
      }
    });

    if (!activeTab) {
      this.onTabClick(this.tabs[0]);
    } else {
      const tabContent = this.getTabContentElementByTab(activeTab);
      this.setActiveTabContent(tabContent);
    }
  }

  setActiveTab(tab) {
    this.tabs.forEach(_tab => {
      if (tab.dataset.target === _tab.dataset.target) {
        _tab.classList.add('active');
      } else {
        _tab.classList.remove('active');
      }
    });
  }

  setActiveTabContent(tabContent) {
    this.tabContents.forEach(tab => {
      if (this.isNoClass) {
        tab.style.display = 'none';
      } else {
        tab.classList.remove('active');
      }
    });

    if (this.isNoClass) {
      tabContent.style.display = 'block';
    } else {
      tabContent.classList.add('active');
    }
  }

  setActiveMobileTab(activeTab) {
    this.mobileTabs.forEach(tab => {
      tab.classList.remove('active');

      const tabContent = tab.nextElementSibling;

      if (tab.dataset.target === activeTab.dataset.target) {
        tab.classList.add('active');
        tabContent.classList.add('active');

        if (Layout.isMobileLayout()) {
          Accord.expand(tabContent);
          setTimeout(() => {
            ScrollTo.scrollToElement(tab);
          }, 300);
        } else {
          tabContent.style.height = '';
        }

      } else {
        tab.classList.remove('active');
        Accord.collapse(tabContent);
        tabContent.classList.remove('active');

        if (Layout.isMobileLayout() === false) {
          tabContent.classList.remove('animate');
          tabContent.style.height = '';
        }
      }
    });
  }

  getTabContentElementByTab(tab) {
    const targetSelector = tab.dataset.target;

    const target = this.nodeElement.querySelector(targetSelector);
    if (!target) {
      throw new Error('Target "' + targetSelector + '" not found');
    }

    return target;
  }

  onTabClick(tab) {
    if (tab.classList.contains('active')) {
      return;
    }

    const tabContent = this.getTabContentElementByTab(tab);

    this.setActiveTab(tab);
    this.setActiveTabContent(tabContent);
    this.setActiveMobileTab(tab);

    triggerEvent(this.nodeElement, 'change', {
      detail: {tab, tabContent}
    });
  }

}

class TabsManager {
  static initInstance(nodeElement) {
    return new Tabs(nodeElement);
  }

  static init() {
    document.querySelectorAll('.js-tabs').forEach(block => this.initInstance(block));
  }
}

TabsManager.init();
window.TabsManager = TabsManager;
