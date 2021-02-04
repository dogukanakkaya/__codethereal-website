<template>
    <li class="vote">
        <button @click="voted === 0 && vote(1)">
            <i v-bind:class="[voted === 1 ? 'bi bi-hand-thumbs-up-fill' : 'bi bi-hand-thumbs-up']"></i>
        </button>
        <span>{{ this.newSum }}</span>
        <button @click="voted === 0 && vote(-1)">
            <i v-bind:class="[voted === -1 ? 'bi bi-hand-thumbs-down-fill' : 'bi bi-hand-thumbs-down']"></i>
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
            voteRoute: {
                required: true,
                type: String
            },
            sum: {
                required: true,
                type: Number
            },
            isVoted: {
                required: true,
                type: Number
            }
        },
        data: function() {
            return {
                newSum: this.sum,
                voted: this.isVoted,
            }
        },
        methods: {
            async vote(vote) {
                const {data} = await axios.post(this.voteRoute, {post_id: this.postId, vote})
                if (data.status){
                    this.newSum = Number(this.newSum) + Number(vote)
                    this.voted = vote
                }
            }
        }
    }
</script>
