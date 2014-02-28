$(document).ready(function() {

    /*
$('.star').raty({
                                               readOnly: true,
                                               score: function() {
                                                 return $(this).attr('data-score');
                                               }
                                             });
*/
    var $container = $('#container');
    $container.isotope({
        masonry: {
            columnWidth: 2
        },
        sortBy: 'number',
        getSortData: {
            number: function( $elem ) {
                var number = $elem.find('.star').attr('data-score');
                return parseInt( number, 10 );
            },
            alphabetical: function( $elem ) {
                var name = $elem.find('p > a').text();
                /* var name = $elem.find('.name'),*/
                /* itemText = name.length ? name : $elem;*/
                /* console.log("name=",name);*/
                return name;
            }
        }
    });
    
      
    var $optionSets = $('#options .option-set'),
    $optionLinks = $optionSets.find('a');
    $optionLinks.click(function(){
        var $this = $(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
            return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
        key = $optionSet.attr('data-option-key'),
        value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
            // changes in layout modes need extra logic
            changeLayoutMode( $this, options )
        } else {
            // otherwise, apply new options
            $container.isotope( options );
        }
        return false;
    });









/*



                                                                                      var $container = $('#container');


                                                                                       $container.find('.element').each(function(){
                                                                                              var $this = $(this),
                                                                                                  number = parseInt( $this.find('.number').text(), 10 );
                                                                                              if ( number % 7 % 2 === 1 ) {
                                                                                                $this.addClass('width2');
                                                                                              }
                                                                                              if ( number % 3 === 0 ) {
                                                                                                $this.addClass('height2');
                                                                                              }
                                                                                            });
      
                                                                                      // initialize isotope
                                                                                      $container.isotope({
                                                                                          itemSelector: '.element',
                                                                                              masonry : {
                                                                                                columnWidth : 0
                                                                                              },
                                                                                      getSortData : {
 
                                                                                        number : function ( $elem ) {
     
                                                                                          return parseInt( $elem.find('.star').text() );
                                                                                        },
 
                                                                                      }

                                                                                      });

                                                                                      $('#sort-by a').click(function(){
                                                                                        // get href attribute, minus the '#'
                                                                                        var sortName = $(this).attr('href').slice(1);
                                                                                        $('#container').isotope({ sortBy : sortName });
                                                                                        return false;
                                                                                      });

                                                                                      // filter items when filter link is clicked
                                                                                      $('#filters a').click(function(){
                                                                                        var selector = $(this).attr('data-filter');
                                                                                        $container.isotope({ filter: selector });
                                                                                        return false;
                                                                                      });
                                                                                      // change size of clicked element
                                                                                            $container.delegate( '.element', 'click', function(){
                                                                                              $(this).toggleClass('large');
                                                                                              $container.isotope('reLayout');
                                                                                            });

                                                                                            // toggle variable sizes of all elements
                                                                                            $('#toggle-sizes').find('a').click(function(){
                                                                                              $container
                                                                                                .toggleClass('variable-sizes')
                                                                                                .isotope('reLayout');
                                                                                              return false;
                                                                                            });
                                                                                      $('.star').raty({
                                                                                                                                     readOnly: true,
                                                                                                                                     score: function() {
                                                                                                                                       return $(this).attr('data-score');
                                                                                                                                     }
                                                                                                                                   });
                                             
                                                                                                                          */
});

 
 