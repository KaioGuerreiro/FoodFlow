import { useState, useEffect } from "react";
import CadastroUsuario from "./CadastroUsuario";
import CadastroPrato from "./CadastroPrato";
import ListaUsuarios from "./ListaUsuarios";
import ListaPratos from "./ListaPratos";
import Dashboard from "./Dashboard";
import "./App.css";

function App() {
  const [currentPage, setCurrentPage] = useState("dashboard");
  const [usuarios, setUsuarios] = useState([]);
  const [pratos, setPratos] = useState([]);

  // Carregar dados do localStorage
  useEffect(() => {
    const usuariosSalvos = localStorage.getItem("usuarios");
    const pratosSalvos = localStorage.getItem("pratos");

    if (usuariosSalvos) {
      setUsuarios(JSON.parse(usuariosSalvos));
    }
    if (pratosSalvos) {
      setPratos(JSON.parse(pratosSalvos));
    }
  }, []);

  // Salvar usuários no localStorage
  useEffect(() => {
    localStorage.setItem("usuarios", JSON.stringify(usuarios));
  }, [usuarios]);

  // Salvar pratos no localStorage
  useEffect(() => {
    localStorage.setItem("pratos", JSON.stringify(pratos));
  }, [pratos]);

  const adicionarUsuario = (usuario) => {
    const novoUsuario = { ...usuario, id: Date.now() };
    setUsuarios([...usuarios, novoUsuario]);
  };

  const atualizarUsuario = (id, usuarioAtualizado) => {
    setUsuarios(
      usuarios.map((u) => (u.id === id ? { ...usuarioAtualizado, id } : u))
    );
  };

  const excluirUsuario = (id) => {
    setUsuarios(usuarios.filter((u) => u.id !== id));
  };

  const adicionarPrato = (prato) => {
    const novoPrato = { ...prato, id: Date.now() };
    setPratos([...pratos, novoPrato]);
  };

  const atualizarPrato = (id, pratoAtualizado) => {
    setPratos(
      pratos.map((p) => (p.id === id ? { ...pratoAtualizado, id } : p))
    );
  };

  const excluirPrato = (id) => {
    setPratos(pratos.filter((p) => p.id !== id));
  };

  const renderPage = () => {
    switch (currentPage) {
      case "dashboard":
        return (
          <Dashboard
            usuarios={usuarios}
            pratos={pratos}
            onNavigate={setCurrentPage}
          />
        );
      case "cadastro-usuario":
        return (
          <CadastroUsuario
            onVoltar={() => setCurrentPage("dashboard")}
            onSalvar={adicionarUsuario}
          />
        );
      case "lista-usuarios":
        return (
          <ListaUsuarios
            usuarios={usuarios}
            onVoltar={() => setCurrentPage("dashboard")}
            onEditar={atualizarUsuario}
            onExcluir={excluirUsuario}
            onNovo={() => setCurrentPage("cadastro-usuario")}
          />
        );
      case "cadastro-prato":
        return (
          <CadastroPrato
            onVoltar={() => setCurrentPage("dashboard")}
            onSalvar={adicionarPrato}
          />
        );
      case "lista-pratos":
        return (
          <ListaPratos
            pratos={pratos}
            onVoltar={() => setCurrentPage("dashboard")}
            onEditar={atualizarPrato}
            onExcluir={excluirPrato}
            onNovo={() => setCurrentPage("cadastro-prato")}
          />
        );
      default:
        return null;
    }
  };

  return <div className="min-h-screen">{renderPage()}</div>;
}

export default App;
