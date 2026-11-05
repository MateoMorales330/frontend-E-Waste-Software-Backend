//
import express from 'express';
import cors from 'cors';

const app = express();
const PORT = process.env.PORT || 3000;

//1. Permite que vue (en http://localhost:5173) haga peticiones
app.use(cors({
    origin: 'http://localhost:5173'
}));
//2 permite que backend  entienda datos en formato json (para POST/PUT)
app.use(express.json());

//3. Ruta de prueba (Endpoint)
app.get ('/api/test', (req, res) => {
    res.json({message: 'Hola desde el backend!'});
});

//4. Inicia el servidor
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});