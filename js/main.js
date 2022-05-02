(function($){
    jQuery(document).ready(function() {
        
        // In general we have 6 squares

        // Random count form 1 to 3
        var count = Math.floor((Math.random() * 3) + 1);
        console.log(count);

        // Random color
        function getRandomColor() {

            var letters = '0123456789ABCDEF';
            var color = '#';
            
            for (let i = 0; i < 6; i++) {
              color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
          
        // var randomColor = getRandomColor();
        // console.log(randomColor);

        // All available squares
        // var squares = 6 - count;
        // console.log(squares);

        var boxes = $('.box');
        for (let k = 0; k < count; k++){
            let index = Math.floor(Math.random() * boxes.length);
            let item = $(boxes[index]);
            if ( item.hasClass('blue') ) {
                let repeat = Math.floor(Math.random() * boxes.length);
                $(boxes[repeat]).addClass('blue');
            } else {
                item.addClass('blue');
            }
        }

        boxes.each(function (index, el) {
            if ( !$(el).hasClass('blue') ) {
                $(el).css("background-color", getRandomColor());
            }
        });
        // var item = boxes[Math.floor(Math.random() * boxes.length)];
        // console.log(item);

        $('.content').on( 'click', '.box', function(i, e) {
            // console.log( $(this) );
            $(this).toggleClass('chosen');
        });

        $('body').on('click', 'button', function (i, e) {
            let j = 0;
            var checked = $('.chosen');
            // console.log('All checked: ' + checked.length);
            // Error
            if (checked.length == 0) {
                alert('Please choose all blue items...');
            } else {

                var existingBlue = $('.box');
                // console.log('All existing blue: ' + existingBlue.length);
                existingBlue.each(function (ind, elem) {
                    if ($(elem).hasClass('chosen') && $(elem).hasClass('blue')) {
                        j++;
                    }
                });
                // console.log(j);
                if (j == count && checked.length == count) {
                    // alert('GOOD JOB!');
                    var done = confirm("GOOD JOB! Try again?");
                    if (done == true) {
                        location.reload();
                    }
                } else {
                    alert('Please pay attention! Choose all BLUE items');
                }
            }
        });


    });
   
})(jQuery);