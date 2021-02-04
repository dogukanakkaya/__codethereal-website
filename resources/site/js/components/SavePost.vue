<template>
    <button class="save-post" @click="!saved && save()">
        <i v-bind:class="[saved ? 'bi bi-bookmark-fill' : 'bi bi-bookmark']"></i>
    </button>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            postId: {
                required: true,
                type: Number
            },
            savePostRoute: {
                required: true,
                type: String
            },
            isSaved: {
                required: true,
                type: Boolean
            }
        },
        data: function() {
            return {
                saved: this.isSaved,
            }
        },
        methods: {
            async save() {
                const {data} = await axios.post(this.savePostRoute, {post_id: this.postId})
                if(data.status){
                    this.saved = true
                }
            }
        }
    }
</script>
