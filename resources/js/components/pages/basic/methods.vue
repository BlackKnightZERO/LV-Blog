<template>
    <div>
        <h3>methods</h3>
        <p>this number is-> {{ views }}</p>
        <button @click="add">+</button>
        <button @click="subtract">-</button>
        <br>
        <br>

        <div v-if="blogs.length>0">
            <table border>
                <tr>
                    <th>Sl</th>
                    <th>title</th>
                    <th>description</th>
                    <th>id</th>
                </tr>
                <tr v-for="(blog,index) in blogs" :key="index">
                    <td>{{ index+1 }}</td>
                    <td>{{ blog.title }}</td>
                    <td>{{ blog.description }}</td>
                    <td>{{ blog.id }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            views: 0,
            blogs:[],
        }
    },
    methods:{
        add(){
            this.views++
        },
        subtract(){
            this.views--
        }
    },

    created(){
        
        const viewData = 100;//axios data retrieve to view:100
        this.views = viewData; //assign
        
        const blogsData = [ //axios data blog posts;
                {
                    title:'title 1',
                    description: 'description 1',
                    id: 1,
                },
                {
                    title:'title 2',
                    description: 'description 2',
                    id: 2,
                },
                {
                    title:'title 3',
                    description: 'description 3',
                    id: 3,
                },
            ];
        this.blogs = blogsData;  //assign  
    }
}
</script>