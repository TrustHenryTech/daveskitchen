document.addEventListener('click', function(e){
  if (e.target.matches('.add-to-cart')){
    const id = e.target.dataset.id;
    fetch('/add_to_cart.php',{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body: 'product_id='+encodeURIComponent(id)
    }).then(r=>r.json()).then(data=>{
      if (data.success){
        const el = document.getElementById('cart-count');
        if (el) el.textContent = data.count;
        e.target.textContent = 'Added';
        setTimeout(()=> e.target.textContent = 'Add', 1200);
      } else alert('Could not add to cart.');
    });
  }
});
