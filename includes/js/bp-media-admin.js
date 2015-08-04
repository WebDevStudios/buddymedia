// Add custom post type filters
l10n = wp.media.view.l10n = typeof _wpMediaViewsL10n === 'undefined' ? {} : _wpMediaViewsL10n;

wp.media.view.AttachmentFilters.Uploaded.prototype.createFilters = function() {
    var type = this.model.get('type'),
        types = wp.media.view.settings.mimeTypes,
        text;
    if ( types && type )
        text = types[ type ];

    filters = {
        all: {
            text:  text || l10n.allMediaItems,
            props: {
                uploadedTo: null,
                orderby: 'date',
                order:   'DESC'
            },
            priority: 10
        },

        uploaded: {
            text:  l10n.uploadedToThisPost,
            props: {
                uploadedTo: wp.media.view.settings.post.id,
                orderby: 'menuOrder',
                order:   'ASC'
            },
            priority: 20
        }
    };
    // Add post types only for gallery
    if (this.options.controller._state.indexOf('gallery') !== -1) {
        delete(filters.all);
        filters.image = {
            text:  'Images',
            props: {
                type:    'image',
                uploadedTo: null,
                orderby: 'date',
                order:   'DESC'
            },
            priority: 10
        };
        _.each( wp.media.view.settings.postTypes || {}, function( text, key ) {
            filters[ key ] = {
                text: text,
                props: {
                    type:    key,
                    uploadedTo: null,
                    orderby: 'date',
                    order:   'DESC'
                }
            };
        });
    }
    this.filters = filters;
     
}; // End create filters