<template>
    <li class="save-post">
        <button @click="save()">
            <i v-bind:class="[saved ? 'bi bi-bookmark-fill' : 'bi bi-bookmark']"></i>
        </button>
    </li>
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
            },
            authenticated: {
                required: true,
                type: Boolean
            }
        },
        data: function() {
            return {
                saved: this.isSaved,
                auth: this.authenticated
            }
        },
        methods: {
            async save() {
                if (this.auth) {
                    const {data} = await axios.post(this.savePostRoute, {post_id: this.postId})
                    if (data.status) {
                        this.saved = !this.saved
                    }
                }else{
                    this.register()
                }
            }
        }
    }
</script>
