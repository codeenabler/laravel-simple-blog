<template>
    <div class="mb-5 pb-3">
        <div class="card mb-5">
            <div class="card-body">
                <new-comment @created="add"></new-comment>
            </div>
        </div>
        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item pt-4 pb-4" v-for="(comment, index) in items" :key="comment.id">
                    <comment :comment="comment" @deleted="remove(index)"></comment>
                </li>
            </ul>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>
    </div>
</template>

<script>
    import Comment from './Comment.vue';
    import NewComment from './NewComment.vue';
    import collection from '../mixins/collection';

    export default {
        components: { Comment, NewComment },

        mixins: [collection],

        data() {
            return { dataSet: false };
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/comments?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            }
        }
    }
</script>
