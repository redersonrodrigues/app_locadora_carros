<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(titulo, index) in titulos" :key="index" class="text-uppercase">{{ titulo }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj in filteredData" :key="obj.id">
                    <td v-for="(valor, chave) in obj" :key="chave">
                        <span v-if="chave === 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30">
                        </span>
                        <span v-else>
                            {{ valor }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        dados: {
            type: Array,
            required: true
        },
        titulos: {
            type: Array,
            required: true
        }
    },
    // A computed property filteredData é usada para filtrar os dados antes da renderização. 
    // Isso cria um novo objeto com apenas as chaves que estão presentes em titulos.
    computed: {
        filteredData() {
            return this.dados.map(obj => {
                const filteredObj = {};
                Object.keys(obj).forEach(key => {
                    if (this.titulos.includes(key)) {
                        filteredObj[key] = obj[key];
                    }
                });
                return filteredObj;
            });
        }
    }
}
</script>
