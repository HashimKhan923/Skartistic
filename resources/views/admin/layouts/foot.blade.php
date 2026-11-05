<script>
(function(){
  var root=document.documentElement,btn=document.getElementById('theme-btn'),meta=document.getElementById('meta-theme');
  function paint(t){
    root.setAttribute('data-theme',t);
    if(meta)meta.content=t==='dark'?'#0d0a18':'#6655e7';
    if(btn)btn.setAttribute('aria-label',t==='dark'?'Switch to light mode':'Switch to dark mode');
    window.dispatchEvent(new Event('sk-theme'));
  }
  paint(root.getAttribute('data-theme')||'light');
  if(btn)btn.addEventListener('click',function(){
    var next=root.getAttribute('data-theme')==='dark'?'light':'dark';
    try{localStorage.setItem('sk-theme',next)}catch(e){}
    if(!document.startViewTransition||matchMedia('(prefers-reduced-motion: reduce)').matches){paint(next);return}
    var r=btn.getBoundingClientRect(),x=r.left+r.width/2,y=r.top+r.height/2,far=Math.hypot(Math.max(x,innerWidth-x),Math.max(y,innerHeight-y));
    document.startViewTransition(function(){paint(next)}).ready.then(function(){
      root.animate({clipPath:['circle(0px at '+x+'px '+y+'px)','circle('+far+'px at '+x+'px '+y+'px)']},{duration:750,easing:'cubic-bezier(.16,1,.3,1)',pseudoElement:'::view-transition-new(root)'});
    }).catch(function(){});
  });
  if(window.matchMedia)matchMedia('(prefers-color-scheme: dark)').addEventListener('change',function(e){
    var saved=null;try{saved=localStorage.getItem('sk-theme')}catch(err){}
    if(!saved)paint(e.matches?'dark':'light');
  });
  // Bundle blocked or failed: fall back rather than leave anything hidden
  if(root.classList.contains('m-on'))setTimeout(function(){if(!window.__skMotion)root.classList.remove('m-on')},4500);
})();
</script>
@if(view()->shared('adminMotion'))
@vite(['resources/css/admin-motion.css', 'resources/js/admin-motion.js'])
@endif
