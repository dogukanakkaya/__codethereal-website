<template>
    <li class="vote">
        <button class="up" @click="vote(1)"><i class="bi bi-hand-thumbs-up"></i></button>
        <span>{{ this.newCount }}</span>
        <button class="down" @click="vote(-1)"><i class="bi bi-hand-thumbs-down"></i></button>
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
            voteRoute: {
                required: true,
                type: String
            },
            count: {
                required: true,
                type: Number
            }
        },
        data: function() {
            return {
                newCount: this.count
            }
        },
        methods: {
            async vote(vote) {
                const {data} = await axios.post(this.voteRoute, {post_id: this.postId, vote})
                if (data.status){
                    this.newCount = Number(this.newCount) + Number(vote)
                }
            }
        }
    }
</script>
