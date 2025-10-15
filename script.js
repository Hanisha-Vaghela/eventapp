// assets/js/script.js
document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('loginForm');
  if(loginForm){
    loginForm.addEventListener('submit', (e) => {
      const em = loginForm.querySelector('[name=email]').value.trim();
      if(!em.includes('@')) {
        e.preventDefault();
        alert('Enter a valid email.');
      }
    });
  }
  const reg = document.getElementById('registerForm');
  if(reg){
    reg.addEventListener('submit', (e) => {
      const pw = reg.querySelector('[name=password]').value;
      if(pw.length < 6) {
        e.preventDefault();
        alert('Password should be at least 6 characters.');
      }
    });
  }
});
