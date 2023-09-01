/**
 *
 * @description JS functions for manipulating sections of the documentation
 * @license MIT License
 * @author Michael K. Ocansey <https://github.com/mkocansey>
 *
 */

const navAnimation = 'fadeIn';

/**
 * Set the title of the current page.
 * appends the text defined in the first <h1> to what is defined as
 * the default heading in config.php (default_page_title)
 * @returns VoidFunction
 * */
void (setPageTitle = () =>  {
    let page_title = (domElement('.doc-content h1:first-child')) ?
        domElement('.doc-content h1:first-child').textContent :
        '404 | Page not found';
    document.title += ': ' + page_title;
})

/**
 * Highlight the link corresponding to the current link being viewed.
 * retrieves the text after the last '/' of the url and strips off
 * any hash (#) and text after the #
 * @param {string} default_url
 * @returns VoidFunction
 */
void (highlightThisPageInNav = (default_url) => {
    let this_page = (((location.href).split('/')).slice(-1)[0].split('#'))[0];
    if(this_page !== default_url) {
        document.querySelectorAll('nav li a').forEach((el) => {
            if (el.getAttribute('href') === this_page) {
                el.classList.add('selected');
            }
        });
    }
})

/**
 * Activate actions on navigation items
 * @returns VoidFunction
 */
void (activateNavActions = () => {
    document.querySelectorAll('nav li h1, nav li h2').forEach((el) => {
        el.addEventListener('click', () => {
            let cascade_collapse = (el.parentNode.parentNode.parentNode.nodeName.toLowerCase() === 'nav');
            collapseAll(cascade_collapse);
            let next_kid = el.nextElementSibling;
            if(next_kid.classList.contains('open')) {
                next_kid.classList.add('hidden');
                next_kid.classList.remove('open');
                next_kid.classList.remove(navAnimation);
            } else {
                next_kid.classList.toggle('hidden');
                next_kid.classList.toggle('open');
                next_kid.classList.toggle(navAnimation);
            }
        });
    });
})


/**
 * Only one menu item will need to remain open at a time.
 * this ensures all expanded menu items in the nav are collapsed when opening another top level menu item.
 * param cascade_collapse determines if all <ul>s need to be collapsed. to prevent multi level parent menu
 * items from collapsing when navigating down the tree, cascade_collapse is set to false.
 * the value for cascade_collapse is determined in activateNavActions()
 * @param {string} cascade_collapse
 * @returns VoidFunction
 */
void (collapseAll = (cascade_collapse) => {
    let selector = (cascade_collapse) ? 'nav li ul' : 'nav li ul:not(:scope ul)';
    document.querySelectorAll(selector).forEach((el) => {
    if (el.classList.contains('open') && el.classList.contains('hidden')) {
        el.classList.remove('open');
    } else {
        el.classList.add('hidden');
    }
    });
})


/**
 * When the page reloads, the selected nav item will be highlighted BUT collapsed
 * so the user won't know which page they are viewing. un-collapse all parents of
 * the selected nav item, irrespective of how nested the selected item is.
 * @returns VoidFunction
 */
void (openUpNav = () => {
    let elem = domElement('li a.selected');
    if (elem) {
        for (; elem; elem = elem.parentNode) {
            let current_node = elem.nodeName.toLowerCase();
            if (current_node === 'ul') {
                elem.classList.replace('hidden', 'open');
            }
            if (current_node === 'nav') break;
        }
        domElement('li a.selected').parentNode.parentNode.classList.add(navAnimation);
    }
})

/**
 * Make api calls via ajax
 * @param {string} url
 * @param {string} callback
 * @param {string} method
 * @param {Object} data
 * @returns VoidFunction
 */
void (ajaxCall = (url, callback, method = 'GET', data) => {
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.onreadystatechange =  function() {
        if (this.readyState === 4) window [callback](JSON.parse(this.response), this.status);
    }
    xhr.send();
})

/**
 * Callback when cloning a repo
 * @callback cloneCallback
 * @param {Object} data
 * @param {Number} status
 * @returns VoidFunction
 */
void (cloneCallback = (data, status) => {
    if (status === 200  || status === 422 ) {
        let status = data.status;
        let message = data.message;
        let result = data.data;
        domElement('.clone-info').innerHTML += message;
        if((result !== undefined) && parseInt(result.branch) !== 0 && status) {
            ajaxCall(`/cloning?branch=${result.branch}`, 'cloneCallback');
        }
    }
  })

/**
 * make all external links open in a new tab/window
 * @returns VoidFunction
 */
  externalLinksOpenInNewWindow = () => {
    document.querySelectorAll('.doc-content a').forEach((el) => {
        if(el.getAttribute('href').includes('http')) {
            el.setAttribute('target', '_blank');
        }
    });
  }

/**
 * Perform site-wide search
 * @param {string} keyword
 * @returns VoidFunction
 */
void (search = (keyword) => {
    let search_bar = domElement('.search-bar');
      if (keyword.replace(/^(\s)+/g, '') !== '') {
        search_bar.classList.remove('hidden');
        if (keyword.length > 2) {
            domElement('.search-results').innerHTML = '';//`Searching for <b>${keyword}</b>`;
            ajaxCall(`/search?keyword=${keyword}`, 'searchCallback');
        } else {
            domElement('.search-results').innerHTML = '<div>Keyword too short</div>';
        }
      } else {
        search_bar.classList.add('hidden');
    }
  })

/**
 * Display results of search
 * @param {Object} data
 * @param {Number} status
 * @returns VoidFunction
 */
  void (searchCallback = (data, status) => {
      if (status === 200  || status === 422) {
          let status = data.status;
          let message = data.message ?? null;
          let result = data.data ?? null;
          let search_results = domElement('.search-results');
          if (status === true && result.results !== null) {
              search_results.innerHTML = '';
              if (result.total > 0) {
                  for (let x = 0; x < result.results.length; x++) {
                      let this_result = result.results[x];
                      search_results.innerHTML += `<div class="result" 
                        onclick="location.href='${this_result.file}#${sluggable(this_result.heading)}';
                        hide('.search-bar'); domElement('.search').value=''"><b>${this_result.heading}</b><br />${this_result.text}</div>`;
                  }
              } else {
                  search_results.innerHTML = `<div>nothing found</div>`;
              }
          } else {
              search_results.innerHTML = `<div>${message}</div>`;
          }
      }
  })

/**
 * Listen for search shortcuts and focus in search box
 * @param event
 * @returns VoidFunction
 */
 void (listenForSearchShortcut = (event) => {
    document.addEventListener('keydown', (event) => {
        let key_code = event.key.toLowerCase();
        if((event.metaKey && key_code === 'k') || event.ctrlKey && key_code === 'k')
            domElement('.search').focus();
    });
      document.addEventListener('keyup', (event) => {
          let key_code = event.key.toLowerCase();
          if(key_code === '/') domElement('.search').focus();
          if(key_code === 'escape') {
              hide('.search-bar');
              domElement('.search').blur();
              domElement('.search').value = '';
          }
      });
  })

/**
 * Shortcut for querySelector function
 * @param {string} element
 * @returns {Element}
 */
  domElement = (element) => {
    return document.querySelector(element);
  }

/**
 * Shortcut for querySelectorAll
 * @param elements
 * @returns {NodeListOf<*>}
 */
domElements = (elements) => {
    return document.querySelectorAll(elements);
  }

/**
 * Write the search shortcuts into the search box
 * @returns VoidFunction
 */
void (writeCtrlOrCmd = () => {
    domElement('.search-shortcut').innerText = (! navigator.userAgent.toLowerCase().includes('mac os')) ? 'Ctrl+K' : '⌘K';
  })

/**
 * Draw side navigation within article
 * @returns VoidFunction
  */
void (drawSidenav = () => {
    let headings = domElements('.doc-content h2, .doc-content h3, .doc-content h4, .doc-content h5, .doc-content h6');
    let nav = '<ul>';
    let ul_open = false;
    
    headings.forEach((el, index) => {
        el.setAttribute('id', sluggable(el.innerText));
        el.setAttribute('style', 'scroll-margin-top: 6rem');    
        let this_level = parseInt(el.tagName.charAt(1), 10);
        let previous_level = (index > 0) ? parseInt(headings[index-1].tagName.charAt(1), 10) : this_level;
        let next_level = ((index+1) < headings.length) ? parseInt(headings[index+1].tagName.charAt(1), 10) : this_level;
        let link_text = `<a href="#${sluggable(el.innerText)}">${el.innerText}</a>`;
        
        if (this_level > previous_level) {
            nav += '<ul>';
            ul_open = true;
        }
        nav+= `<li>${link_text}`;
        if (ul_open && this_level > next_level) nav += '</li></ul>';
        if (this_level === next_level) nav += '</li>';
    });

    nav += '</ul>';
    domElement('.side-nav').innerHTML = nav;
  })

/**
 * Append IDs to headings to enable jump-to-heading
 * @returns VoidFunction
 */
  void (setHeadingIds = () => {
    let headings = domElements('.doc-content h2, .doc-content h3, .doc-content h4, .doc-content h5, .doc-content h6');
    headings.forEach((el) => {
       el.setAttribute('id', sluggable(el.innerText));
    });
  })

/**
 * Turn string into a slug
 * @param {string} text
 * @returns {string}
 */
  sluggable = (text) => {
    let find = /[\s.!@#\$%\^&*\(\)+{}\/\\<>:;\?\[\]=`~,|]/g;
    return text.replace(find, '-').toLowerCase();
  }

/**
 * Show an element
 * @param element
 * @returns VoidFunction
 */
void (show = (element) => { domElement(element).classList.remove('hidden'); })

/**
 * Hide an element
 * @param {string} element
 * @returns VoidFunction
 */
void (hide = (element) => { domElement(element).classList.add('hidden'); })

/**
 * Set page theme to dark or light
 * @param {boolean} onload
 * @returns VoidFunction
 */
  void (setTheme = (onload = false) => {
    // let theme;
    let system_theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    let theme = (onload) ?
        window.localStorage.getItem('theme') || system_theme :
        ((domElement('html').classList.contains('dark')) ? 'light' : 'dark');

    (theme === 'light') ?
        domElement('html').classList.remove('dark') :
        domElement('html').classList.add('dark');

    window.localStorage.setItem('theme', theme);
  })

/**
 * Prepend navigation links with current documentation version number
 * @param {string} version
 * @returns VoidFunction
 */
void (prependNavItemsWithPath = (path) => {
        domElements('nav a').forEach((el) => {
           el.setAttribute('href', `${path}${el.getAttribute('href')}`);
        });
    })