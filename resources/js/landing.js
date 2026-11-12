(function(){
 var b=document.getElementById('burger'),l=document.getElementById('links');
 b.addEventListener('click',function(){var o=l.classList.toggle('open');b.setAttribute('aria-expanded',o)});
 l.addEventListener('click',function(){l.classList.remove('open')});
 var els=document.querySelectorAll('.rv');
 if(!('IntersectionObserver' in window)){els.forEach(function(e){e.classList.add('in')});return}
 var io=new IntersectionObserver(function(en){en.forEach(function(x){if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target)}})},{threshold:.12});
 els.forEach(function(e){io.observe(e)});
})();
