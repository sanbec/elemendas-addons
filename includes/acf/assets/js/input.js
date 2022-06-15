(function($) {
  var active_item;
  var item_width = 120;
  var item_height = 90+15;
  const { __, _x, _n, _nx } = wp.i18n;

  jQuery(document).on('click', 'li[data-svg]', function() {
    var val = jQuery(this).attr('data-svg');
    active_item.find('input').val(val);
    active_item.find('.acf-icon-picker__svg').html(
      '<img src="' +
        jQuery(this)
          .find('img')
          .attr('src') +
        '" alt=""/>'
    );
    jQuery('.acf-icon-picker__popup-holder').trigger('close');
    jQuery('.acf-icon-picker__popup-holder').remove();
    jQuery('.acf-icon-picker__img input').trigger('change');
    
    active_item
      .parents('.acf-icon-picker')
      .find('.acf-icon-picker__remove')
      .addClass('acf-icon-picker__remove--active');
  });

  function initialize_field($el) {
    $el.find('.acf-icon-picker__img').on('click', function(e) {
      e.preventDefault();
      var is_open = true;
      active_item = $(this);

      if (allSVGs.length == 0) {
        var list = '<p>' + i10nStr.no_icons_msg + '</p>';
      } else {
        var list = `<ul id="icons-list">`;
        list += `</ul>`;
      }


      var dirs = '<ul class="acf-icon-picker__families">';
      dirs += '<h4>'+__('Icon Families','elemendas-addons')+'</h4>';
      dirs += '<li><a class="acf-icon-picker__all-families" href="#">' + __('All','elemendas-addons') + '</a></li>';
      if (iconSets.length > 0) {
        for (let x in iconSets) {
          if (iconSets[x]==='Uploaded Icons')
            dirs += '<li><a class="acf-icon-picker__uploaded" href="#">' + iconSets[x] + '</a></li>'
          else
            dirs += '<li><a href="#">' + iconSets[x] + '</a></li>';

        }
      }
      dirs += '<p>' + i10nStr.upload_icons_msg +'</p>';
      dirs += '</ul>';

      jQuery('body').append(
        '<div class="acf-icon-picker__popup-holder">' +
          '<div class="acf-icon-picker__popup">' + dirs +
            '<a class="acf-icon-picker__popup__close" href="javascript:">' +
                '<img src="'+plugin_url+'assets/img/cross.svg" style="width: 2em;" alt="'+ __('close','elemendas-addons') +'">' +
            '</a>' +
            '<div class="acf-icon-picker__popup__title"><h4>' + __('Icon Picker - Choose icon','elemendas-addons') + '</h4>' +
            '<span>' + __('Displaying the icons for all families','elemendas-addons') + '</span></div>' +
            '<input class="acf-icon-picker__filter" type="search" id="filterIcons" placeholder="' + __('Start typing to filter icons','elemendas-addons') + '" />' +
            '<div class="acf-icon-picker__popup__icons">' + list + '</div>' +
          '</div>' +
        '</div>'
      );

      jQuery('.acf-icon-picker__popup-holder').on('close', function() {
        is_open = false;
      });

      var $list = $('#icons-list');
      var margin = 200; // number of px to show above and below.

      var width = jQuery('.acf-icon-picker__popup__icons').width()  ;
      var columns = (width / item_width)>>0 ;
      $('.acf-icon-picker__popup__icons').css('--columns',columns);
      var familySVGs = allSVGs;
      var svgs = familySVGs;

      function setListHeight() {
        var total_lines = Math.ceil(svgs.length / columns);
        $list.height(total_lines * item_height);
      }

      function removeAllItems() {
        $('[data-acf-icon-index]').each(function(i, el) {
          var $el = $(el);
          $el.remove();
        });
      }

      function render() {
        if (!is_open) return;

        var scroll_top = $('.acf-icon-picker__popup__icons').scrollTop();
        var scroll_min = scroll_top - item_height - margin;
        var scroll_max = scroll_top + $('.acf-icon-picker__popup__icons').height() + margin;
        // Get the index of the first and last element from array we will show.
        var index_min = Math.ceil(scroll_min / item_height) * columns;
        var index_max = Math.ceil(scroll_max / item_height) * columns;

        // remove unneeded items
        $('[data-acf-icon-index]').each(function(i, el) {
          var $el = $(el);
          var index = $el.attr('data-acf-icon-index');
          var icon = $el.attr('data-svg');
          // Check if we have the element in the resulting array.
          var elementExist = function() {
            return svgs.find(function (svg) {
              return svg.icon === icon;
            });
          }

          if (index < index_min || index > index_max || !elementExist()) {
            $el.remove();
          }
        });
        if (svgs.length) {
          if (index_min < 0) index_min = 0;
          if (index_max > svgs.length) index_max = svgs.length;


          for (var i = index_min; i < index_max; i++) {
            if (i < 0 || i >= svgs.length) continue;
            var svg = svgs[i];
            // Calculate the position of the item.
            var y = ((i / columns)>>0) * item_height;
            var x = i % columns * item_width;

            // If we already have the element visible we can continue
            var $el = $('[data-acf-icon-index="' + i + '"][data-svg="' + svg.icon + '"]');
            // If item already exist we can skip.
            if ($el.length) continue;

            $el = $(
              '<li>' +
                '<div class="acf-icon-picker__popup-svg">' +
                  '<img src="" alt=""/>' +
                '</div>' +
                '<span class="icons-list__name"></span>' +
              '</li>'
            );

            // We use attr instead of data since we want to use css selector.
            $el.attr({
              'data-svg': svg.icon,
              'data-acf-icon-index': i
            }).css({
              transform: 'translate(' + x + 'px, ' + y + 'px)'
            });
            $el.find('.icons-list__name').text(svg['name'].replace(
              /[-_]/g,
              ' '
            ));
            $el.find('img').attr('src', path + svg['icon']);
            $list.append($el);
          }
        } else {
            addicon = '<p>' + i10nStr.upload_icons_msg +'</p>';
            $list.append(addicon);

        }
        requestAnimationFrame(render);
      }
      if (svgs.length) {
        setListHeight();
        render();
      }

      function filterIcons(wordToMatch) {
        return familySVGs.filter(icon => {
          var name = icon.name.replace(/[-_]/g, ' ');
          const regex = new RegExp(wordToMatch, 'gi');
          return name.match(regex);
        });
      }

      $('#filterIcons').on('focus', function(e) {
        e.stopPropagation();
        $(this).val('');
      });

      $('#filterIcons').on('keyup focus', function(e) {
        e.stopPropagation();
        svgs = filterIcons($(this).val());
        removeAllItems();
        setListHeight();
      });

      // Family filtering

      function filterFamilyIcons(family) {
        return allSVGs.filter(icon => {
          var name = icon.icon;
          const regex = new RegExp(family, 'gi');
          return name.match(regex);
        });
      }

      $('.acf-icon-picker__families > li > a:not(.acf-icon-picker__all-families)').click( function(e) {
        e.stopPropagation();
        familySVGs = filterFamilyIcons($(this).html());
        svgs = filterIcons($('#filterIcons').val());
        $('.acf-icon-picker__popup__title span').html(__('Displaying icons for the family','elemendas-addons') + ' <strong>' + $(this).html() + '</strong>' );
        removeAllItems();
        setListHeight();
      });

      $('.acf-icon-picker__families > li > a.acf-icon-picker__all-families').click( function(e) {
        e.stopPropagation();
        familySVGs = allSVGs;
        svgs = filterIcons($('#filterIcons').val());
        $('.acf-icon-picker__popup__title span').html( __('Displaying the icons for all families','elemendas-addons') );
        removeAllItems();
        setListHeight();
      });

      // Closing
      $('.acf-icon-picker__popup__close').on('click', function(e) {
        e.stopPropagation();
        is_open = false;
        $('.acf-icon-picker__popup-holder').remove();
      });
    });

    // show the remove button if there is an icon selected
    if ($el.find('input').val().length != 0) {
      $el
        .find('.acf-icon-picker__remove')
        .addClass('acf-icon-picker__remove--active');
    }

    $el.find('.acf-icon-picker__remove').on('click', function(e) {
      e.preventDefault();
      var parent = $(this).parents('.acf-icon-picker');
      parent.find('input').val('');
      parent
        .find('.acf-icon-picker__svg')
        .html('<span class="acf-icon-picker__svg--span">+</span>');

        jQuery('.acf-icon-picker__img input').trigger('change');

        parent
        .find('.acf-icon-picker__remove')
        .removeClass('acf-icon-picker__remove--active');
    });
  }

  if (typeof acf.add_action !== 'undefined') {
    acf.add_action('ready append', function($el) {
      acf.get_fields({ type: 'icon_picker' }, $el).each(function() {
        initialize_field($(this));
      });
    });
  }
})(jQuery);
