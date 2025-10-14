import { useState } from "react";
import CadastroUsuario from "./CadastroUsuario";
import CadastroPrato from "./CadastroPrato";
import "./App.css";

function App() {
  const [currentPage, setCurrentPage] = useState("home");

  if (currentPage === "usuario") {
    return <CadastroUsuario onVoltar={() => setCurrentPage("home")} />;
  }

  if (currentPage === "prato") {
    return <CadastroPrato onVoltar={() => setCurrentPage("home")} />;
  }

  return (
    <div className="flex justify-center items-center min-h-screen bg-gradient-to-br from-purple-100 to-pink-300 p-6">
      <div className="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8">
        <h1 className="text-3xl font-bold text-center text-purple-700 mb-8">
          FoodFlow
        </h1>
        <p className="text-center text-gray-600 mb-8">
          Sistema de Gerenciamento de Restaurante
        </p>

        <div className="space-y-4">
          <button
            onClick={() => setCurrentPage("usuario")}
            className="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 transition"
          >
            Cadastrar Usuário
          </button>

          <button
            onClick={() => setCurrentPage("prato")}
            className="w-full bg-green-600 text-white p-3 rounded-lg font-semibold hover:bg-green-700 transition"
          >
            Cadastrar Prato
          </button>
        </div>
      </div>
    </div>
  );
}

export default App;
