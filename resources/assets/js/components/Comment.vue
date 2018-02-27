<template>
    <div :id="'comment-'+id">
        <h6 class="mb-3">
            <a href="#"
                v-text="comment.owner.name">
            </a> said <span v-text="ago"></span>
        </h6>
        <div v-if="editing">
            <form @submit="update">
                <div class="form-group">
                    <wysiwyg v-model="body"></wysiwyg>
                </div>

                <button class="btn btn-xs btn-primary">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
            </form>
        </div>

        <div v-else v-html="body" class="mb-3"></div>

        <div v-if="authorize('owns', comment)">
            <button class="btn btn-xs btn-secondary mr-1" @click="editing = true" v-if="! editing">Edit</button>
            <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['comment'],

        data() {
            return {
                editing: false,
                id: this.comment.id,
                body: this.comment.body,
                isBest: this.comment.isBest,
            };
        },

        computed: {
            ago() {
                return moment(this.comment.created_at).fromNow() + '...';
            }
        },

        created () {
            window.events.$on('best-comment-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch(
                    '/comments/' + this.id, {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {
                axios.delete('/comments/' + this.id);

                this.$emit('deleted', this.id);
            },

            markBestComment() {
                axios.post('/comments/' + this.id + '/best');

                window.events.$emit('best-comment-selected', this.id);
            }
        }
    }
</script>
