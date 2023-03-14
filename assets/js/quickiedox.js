/**
 *
 * @description JS functions for manipulating sections of the documentation
 * @author Michael K. Ocansey (https://github.com/mkocansey)
 *
 */

const navAnimation = 'animate__fadeIn';

/**
 * set the title of the current page.
 * appends the text defined in the first <h1> to what is defined as
 * the default heading in config.php (default_page_title)
 * */
setPageTitle = () =>  {
    let page_title = (document.querySelector('.doc-content h1:first-child')) ?
        document.querySelector('.doc-content h1:first-child').textContent :
        '404 | Page not found';
    document.title += ': ' + page_title;
}

/**
 * highlight the link corresponding to the current link being viewed.
 * retrieves the text after the last '/' of the url and strips off
 * any hash (#) and text after the #
 */
highlightThisPageInNav = (default_url) => {
    let this_page = (((location.href).split('/')).slice(-1)[0].split('#'))[0];
    if(this_page !== default_url) {
        document.querySelectorAll('nav li a').forEach((el) => {
            if (el.getAttribute('href').includes(this_page)) {
                el.classList.add('selected');
            }
        });
    }
}

/**
 *
 */
activateNavActions = () => {
    document.querySelectorAll('nav li h1, nav li h2').forEach((el) => {
        el.addEventListener('click', () => {
            let cascade_collapse = (el.parentNode.parentNode.parentNode.nodeName.toLowerCase() === 'nav');
            collapseAll(cascade_collapse);
            let next_kid = el.nextElementSibling;
            if(next_kid.classList.contains('open')) {
                next_kid.classList.add('hidden');
                next_kid.classList.remove('open');
                next_kid.classList.remove('animate__animated');
                next_kid.classList.remove(navAnimation);
            } else {
                next_kid.classList.toggle('hidden');
                next_kid.classList.toggle('open');
                next_kid.classList.toggle('animate__animated');
                next_kid.classList.toggle(navAnimation);
            }
        });
    });
}


/**
 * only one menu item will need to remain open at a time.
 * this ensures all expanded menu items in the nav are collapsed when opening another top level menu item.
 * @param cascade_collapse determines if all <ul>s need to be collapsed. to prevent multi level parent menu
 * items from collapsing when navigating down the tree, cascade_collapse is set to false.
 * the value for cascade_collapse is determined in activateNavActions()
 */
collapseAll = (cascade_collapse) => {
    let selector = (cascade_collapse) ? 'nav li ul' : 'nav li ul:not(:has(ul))';
    document.querySelectorAll(selector).forEach((el) => {
    if(el.classList.contains('open') && el.classList.contains('hidden')) {
        el.classList.remove('open');
    } else {
        el.classList.add('hidden');
    }
    });
}


/**
 * when the page reloads, the selected nav item will be highlighted BUT collapsed
 * so the user won't know which page they are viewing. un-collapse all parents of
 * the selected nav item, irrespective of how nested the selected item is.
 */
openUpNav = () => {
    let elem = document.querySelector('li a.selected');
    if(elem) {
        for (; elem; elem = elem.parentNode) {
            let current_node = elem.nodeName.toLowerCase();
            if (current_node === 'ul') {
                elem.classList.replace('hidden', 'open');
            }
            if (current_node === 'nav') break;
        }
        document.querySelector('li a.selected').parentNode.parentNode.classList.add('animate__animated', navAnimation);
    }
}

ajaxCall = (url, callback, method = 'GET', data) => {
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.onreadystatechange =  function() {
        if (this.readyState == 4) window [callback](JSON.parse(this.response), this.status);
    }
    xhr.send();
  }

  cloneCallback = (data, status) => {
    if ( status == 200  || status == 422 ) {
        let status = data.status;
        let message = data.message;
        let result = data.data;
        document.querySelector('.clone-info').innerHTML += message;
        if( (result !== undefined) && parseInt(result.branch) !== 0 && status) {
            ajaxCall(`/clone?branch=${result.branch}`, 'cloneCallback');
        }
    }
  }