// Client-side validation and small UI helpers
document.addEventListener('DOMContentLoaded', function(){
    // Registration form validation
    var regForm = document.querySelector('form[action][method="post"]');
    if(regForm && regForm.querySelector('input[name="email"]')){
        regForm.addEventListener('submit', function(e){
            var email = regForm.querySelector('input[name="email"]').value.trim();
            var name = regForm.querySelector('input[name="name"]').value.trim();
            if(name === '' || email === ''){
                e.preventDefault();
                alert('Please enter your name and a valid email.');
                return false;
            }
        });
    }

    // Create event form validation (admin)
    var createForm = document.querySelector('form#create-event');
    if(createForm){
        createForm.addEventListener('submit', function(e){
            var title = createForm.querySelector('input[name="title"]').value.trim();
            var date = createForm.querySelector('input[name="event_date"]').value.trim();
            var file = createForm.querySelector('input[name="image"]').files[0];
            if(title === '' || date === ''){
                e.preventDefault();
                alert('Please provide a title and date for the event.');
                return false;
            }
            if(file){
                var maxSize = 2 * 1024 * 1024; // 2MB
                if(file.size > maxSize){
                    e.preventDefault();
                    alert('Image is too large (max 2MB).');
                    return false;
                }
                var allowed = ['image/jpeg','image/png','image/gif'];
                if(allowed.indexOf(file.type) === -1){
                    e.preventDefault();
                    alert('Allowed image types: JPG, PNG, GIF.');
                    return false;
                }
            }
        });
    }
});
