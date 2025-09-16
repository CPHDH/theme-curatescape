const toggleBoolean = (el, attr) => {
  el.getAttribute(attr) == "false" ? el.setAttribute(attr, "true") : el.setAttribute(attr, "false");
}
const toggleMenu = (body, menubutton)=>{
  toggleBoolean(menubutton, 'aria-expanded');
  body.classList.toggle('expanded');
}
const doMenu = ()=>{
  // CLONE FOOTER MENU TO HEADER
  let body = document.querySelector('body');
  let overlay = document.querySelector('#overlay');
  if(!overlay) return;
  let headercontainer = document.querySelector('#header-nav-main');
  if(!headercontainer) return;
  let menubutton = document.querySelector('#menu.button');
  if(!menubutton) return;
  let menutarget = menubutton.attributes.href.nodeValue;
  if(!menutarget) return;
  let menucontent = document.querySelector(menutarget + ' nav');
  let clone = menucontent.parentElement.cloneNode(true); // clone @container
  if(!clone) return;
  let elements = clone.querySelectorAll('[id*=footer],[name*=footer],[aria-label*=footer]');
  if(!elements) return;
  elements.forEach(el => {
    const regex = new RegExp('footer', "gi");
    el.id = el.id.replace(regex,'header');
    if(el.hasAttribute('aria-label')){
      el.setAttribute('aria-label', el.getAttribute('aria-label').replace(regex,'Header'));
    }
    if(el.hasAttribute('name')){
      el.setAttribute('name', el.getAttribute('name').replace(regex,'header'));
    }
  });
  headercontainer.appendChild(clone);
  // OVERLAY CLICK
  overlay.addEventListener('click', (e)=>{
    toggleMenu(body,menubutton);
  });
  // MENU CLICK
  menubutton.addEventListener('click', (e)=>{
    e.preventDefault();
    toggleMenu(body,menubutton);
  });
  // ESC KEY
  document.onkeydown = (e) => {
    e = e || window.event;
    var isEscape = false;
    if ("key" in e) {
      isEscape = e.key === "Escape" || e.key === "Esc";
    } else {
      isEscape = e.keyCode === 27;
    }
    if(isEscape && body.classList.contains('expanded')) toggleMenu(body,menubutton);
  };
}
document.addEventListener('readystatechange',()=>{
  doMenu();
});
